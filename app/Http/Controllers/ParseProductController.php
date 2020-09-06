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
        // TODO: If page doesn't exist, if it isn't product page (exceptions handler)
        $this->productDetailsModel->product_url = $request->input('product-url');
        $this->productDetailsModel->source_web = parse_url($request->input('product-url'), PHP_URL_HOST);

        $dom = $this->document->loadHtmlFile($request->input('product-url'));
        $this->parseAndSaveProductNameAndImage($dom);
        $this->parseAndSaveProductPrice($dom);

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

        return redirect()->route('product-info')->with([
            'status' => __('Produkta dati veiksmīgi tika paņemti un saglabāti!'),
            'main_data' => $this->productData,
            'price_data' => $productPrices
        ]);
    }

    public function parseAndSaveProductNameAndImage($dom)
    {
        $productNode = $dom->find('.product-righter h1')[0]->text();
        $productImage = $dom->find('.product-gallery-slider__slide__inner img')[0]->getAttribute('src');
        $productName = $this->escapeCRLF($productNode);
        $this->downloadProductImage($productImage, $productName);

        $product = $this->productDetailsModel->where('product_url', $this->productDetailsModel->product_url)->get();

        if (!isset($product[0])) {
            $this->productDetailsModel->product_name = $productName;
            $this->productDetailsModel->product_image_url = strtolower(str_replace(' ', '-', $productName)) . '.png';
            $this->productDetailsModel->save();
        } else {
            $this->productDetailsModel = $product[0];
        }
    }

    public function downloadProductImage($url, $productName)
    {
        $fileName = strtolower(str_replace(' ', '-', $productName));
        $imageDirectory = storage_path('app/public') . '/' . $fileName . '.png';

        if (!file_exists(storage_path('app/public') . '/' . $fileName . '.png')) {
            file_put_contents($imageDirectory, file_get_contents($url));
        }
    }

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

    public function checkCurrency($productNode)
    {
        foreach (self::CURRENCIES as $currency) {
            if (strpos($productNode, $currency)) {
                return $currency;
            }
        }
    }

    public function escapeCRLF($text)
    {
        return trim(str_replace(["\r\n", "\n", "\r"], ' ', $text));
    }
}
