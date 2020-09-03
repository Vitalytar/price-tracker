<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DiDom\Document;
use App\ProductPrice;
use App\Product;

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

    public function __construct(Document $document, ProductPrice $productPriceModel, Product $productDetailsModel)
    {
        $this->document = $document;
        $this->productPriceModel = $productPriceModel;
        $this->productDetailsModel = $productDetailsModel;
    }

    /**
     * Parse necessary URL and gather necessary info about product
     *
     * @param Request $request
     */
    public function parse(Request $request)
    {
        // TODO: If page doesn't exist, if it isn't product page (exceptions handler)
        $this->productDetailsModel->product_url = $request->input('product-url');
        $dom = $this->document->loadHtmlFile($request->input('product-url'));
        $this->parseAndSaveProductName($dom);
        $this->parseAndSaveProductPrice($dom);

        return redirect()->back()->with('status', 'Product data successfully parsed and stored into database!');
    }

    public function parseAndSaveProductName($dom)
    {
        $productNode = $dom->find('.product-righter h1')[0]->text();
        $productName = $this->escapeCRLF($productNode);
        $this->productDetailsModel->product_name = $productName;
        $this->productDetailsModel->product_image_url = $productName;

        $this->productDetailsModel->save();
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

            $this->productPriceModel->product_relation_id = '5';
            $this->productPriceModel->product_price = $productPrice;
        }

        $this->productPriceModel->save();
    }

    public function escapeCRLF($text)
    {
        return str_replace(["\r\n", "\n", "\r"], ' ', $text);
    }
}
