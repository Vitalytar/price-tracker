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
        $dom = $this->document->loadHtmlFile($request->input('product-url'));
        $this->parseAndSaveProductNameAndImage($dom);
        $this->parseAndSaveProductPrice($dom);

        if ($request->user()) {
            $this->userRequestedProductModel->user_id = $request->user()->id;
            $this->userRequestedProductModel->requested_product_id = $this->productDetailsModel->id;
            $this->userRequestedProductModel->save();
        }

        return redirect()->back()->with('status', 'Product data successfully parsed and stored into database!');
    }

    public function parseAndSaveProductNameAndImage($dom)
    {
        $productNode = $dom->find('.product-righter h1')[0]->text();
        $productImage = $dom->find('.product-gallery-slider__slide__inner img')[0]->getAttribute('src');
        $productName = $this->escapeCRLF($productNode);
        $this->downloadProductImage($productImage);

        $product = $this->productDetailsModel->where('product_url', $this->productDetailsModel->product_url)->get();

        if (!isset($product[0])) {
            $this->productDetailsModel->product_name = $productName;
            $this->productDetailsModel->product_image_url = $productImage;
            $this->productDetailsModel->save();
        } else {
            $this->productDetailsModel = $product[0];
        }
    }

    public function downloadProductImage($url)
    {
        // TODO: get name from website and set it there somehow
        file_put_contents(storage_path('app') . '/flower.png', file_get_contents($url));
    }

    public function parseAndSaveProductPrice($dom)
    {
        $productNode = $dom->find('.product-price-details__price');

        if (!empty($productNode)) {
            $productNode = $productNode[0]->text();
            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productNode), $result);
            $productPrice = implode('.', $result[0]);

            $this->productPriceModel->product_relation_id = $this->productDetailsModel->id;
            $this->productPriceModel->product_price = $productPrice;
        } else {
            $productNode = $dom->find('.product-price-details__block')[0]->text();

            preg_match_all('!\d+!', str_replace(["\r\n", "\n", "\r"], ' ', $productNode), $result);
            $productPrice = implode('.', $result[0]);

            $this->productPriceModel->product_relation_id = $this->productDetailsModel->id;
            $this->productPriceModel->product_price = $productPrice;
        }

        $this->productPriceModel->save();
    }

    public function escapeCRLF($text)
    {
        return str_replace(["\r\n", "\n", "\r"], ' ', $text);
    }
}
