<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DiDom\Document;
use App\ProductPrice;
use App\Product;
use App\UserProductRequest;

/**
 * Class ParseProductController
 *
 * @package App\Http\Controllers
 */
class ParseProductController extends Controller
{
    const CURRENCIES = [
        '€',
        '$',
        '₽'
    ];

    const AVAILABLE_WEBSITES = [
        'www.1a.lv',
        'www.rdveikals.lv'
    ];

    const ESCAPE_RUS = [
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
        'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
    ];

    /**
     * @var Document
     */
    protected $document;

    /**
     * @var ProductPrice
     */
    protected $productPriceModel;

    /**
     * @var Product
     */
    protected $productDetailsModel;

    /**
     * @var UserProductRequest
     */
    protected $userRequestedProductModel;

    /**
     * @var array
     */
    protected $productData = [];

    /**
     * ParseProductController constructor.
     *
     * @param Document           $document
     * @param ProductPrice       $productPriceModel
     * @param Product            $productDetailsModel
     * @param UserProductRequest $userRequestedProductModel
     */
    public function __construct(
        Document $document,
        ProductPrice $productPriceModel,
        Product $productDetailsModel,
        UserProductRequest $userRequestedProductModel
    ) {
        $this->document = $document;
        $this->productPriceModel = $productPriceModel;
        $this->productDetailsModel = $productDetailsModel;
        $this->userRequestedProductModel = $userRequestedProductModel;
    }

    /**
     * Parse necessary URL and gather necessary info about product
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function parse(Request $request)
    {
        $this->productDetailsModel->product_url = $request->input('product-url');
        $sourceWeb = parse_url($request->input('product-url'), PHP_URL_HOST);
        $this->productDetailsModel->source_web = $sourceWeb;

        if (!in_array($sourceWeb, self::AVAILABLE_WEBSITES)) {
            return redirect()->back()->with(
                [
                    'warning' => __('Dotā vietne netiek atbalstīta!')
                ]
            );
        }

        $dom = $this->document->loadHtmlFile($request->input('product-url'));

        if ($sourceWeb == 'www.1a.lv') {
            $this->parseProductData($dom, '.product-righter h1', '.product-gallery-slider__slide__inner img', $sourceWeb);
            $this->parseAndSaveProductPrice($dom);
        } else {
            if ($sourceWeb == 'www.rdveikals.lv') {
                $this->parseProductData($dom, 'h1', 'img', $sourceWeb);
                $this->rdParsePrice($dom);
            }
        }

        $this->userRequestedProductModel->user_id = ($request->user()) ? $request->user()->id : 0;
        $this->userRequestedProductModel->requested_product_id = $this->productDetailsModel->id;
        $this->userRequestedProductModel->save();

        $this->productData = [
            'product_name' => $this->productDetailsModel->product_name,
            'product_image' => $this->productDetailsModel->product_image_url
        ];

        $prices = $this->productPriceModel->where('product_relation_id', $this->productDetailsModel->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($prices as $price) {
            $productPrices[] = [
                'price' => $price->getAttribute('product_price'),
                'currency' => $price->getAttribute('currency'),
                'date' => $price->getAttributes()['created_at']
            ];
        }

        return redirect()->route('product-info')->with(
            [
                'status' => __('Produkta dati veiksmīgi tika paņemti un saglabāti!'),
                'main_data' => $this->productData,
                'price_data' => $productPrices,
                'source_url' => $this->productDetailsModel->product_url
            ]
        );
    }

    /**
     * @param $dom
     * @param $productNameSelector
     * @param $imageSelector
     * @param $source
     */
    public function parseProductData($dom, $productNameSelector, $imageSelector, $source)
    {
        $productName = $dom->find($productNameSelector)[0]->text();

        if ($source == 'www.rdveikals.lv') {
            $productImage = 'https://www.rdveikals.lv/' . $dom->find($imageSelector)[3]->getAttribute('src');
        } else {
            $productImage = $dom->find($imageSelector)[0]->getAttribute('src');
        }

        $productName = $this->escapeCRLF($productName);
        $this->downloadProductImage($productImage, $productName);
        $product = $this->productDetailsModel->where('product_url', $this->productDetailsModel->product_url)->get();

        if (!isset($product[0])) {
            $this->productDetailsModel->product_name = str_replace(self::ESCAPE_RUS, '', $productName);
            $this->productDetailsModel->product_image_url = strtolower(str_replace([' ', '/'], '-', $productName))
                . '.png';
            $this->productDetailsModel->save();
        } else {
            $this->productDetailsModel = $product[0];
        }
    }

    /**
     * @param $dom
     */
    public function rdParsePrice($dom)
    {
        $productPrice = $dom->find('.price');

        if (!empty($productPrice)) {
            $productPrice = $productPrice[0]->text();
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productPrice), $result);
            $productPrice = implode('.', str_replace(' ', '', $result[0]));
            $currency = $this->checkCurrency($dom->find('.price')[0]->text());
            $this->productPriceModel->product_relation_id = $this->productDetailsModel->id;
            $this->productPriceModel->product_price = $productPrice;
            $this->productPriceModel->currency = $currency;
        }

        $this->productPriceModel->save();
    }

    /**
     * @param $dom
     */
    public function parseAndSaveProductPrice($dom)
    {
        $productNode = $dom->find('.product-price-details__price');

        if (!empty($productNode)) {
            $productNode = $productNode[0]->text();
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productNode), $result);
            $productPrice = implode('.', $result[0]);
            $currency = $this->checkCurrency($productNode);

            $this->productPriceModel->product_relation_id = $this->productDetailsModel->id;
            $this->productPriceModel->product_price = $productPrice;
            $this->productPriceModel->currency = $currency;
        } else {
            $productNode = $dom->find('.product-price-details__block')[0]->text();

            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productNode), $result);
            $productPrice = implode('.', $result[0]);

            $this->productPriceModel->product_relation_id = $this->productDetailsModel->id;
            $this->productPriceModel->product_price = $productPrice;
        }

        $this->productPriceModel->save();
    }

    /**
     * @param $url
     * @param $productName
     */
    public function downloadProductImage($url, $productName)
    {
        $fileName = str_replace(
            self::ESCAPE_RUS, '', strtolower(str_replace([' ', '/'], '-', $productName))
        );
        $imageDirectory = storage_path('app' . DIRECTORY_SEPARATOR . 'public') . DIRECTORY_SEPARATOR . $fileName . '.png';

        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'public') . DIRECTORY_SEPARATOR . $fileName . '.png')) {
            file_put_contents($imageDirectory, file_get_contents($url));
        }
    }

    /**
     * @param $productNode
     *
     * @return mixed
     */
    public function checkCurrency($productNode)
    {
        foreach (self::CURRENCIES as $currency) {
            if (strpos($productNode, $currency)) {
                return $currency;
            }
        }
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function escapeCRLF($text)
    {
        return trim(str_replace(["\r\n", "\n", "\r"], ' ', $text));
    }
}
