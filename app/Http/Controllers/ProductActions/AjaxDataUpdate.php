<?php

namespace App\Http\Controllers\ProductActions;

use Illuminate\Http\Request;
use DiDom\Document;
use App\Product;
use App\Trlprice\Parsers\PriceParsers;
use App\UserProductRequest;

/**
 * Class AjaxDataUpdate
 *
 * @package App\Http\Controllers\ProductActions
 */
class AjaxDataUpdate
{
    /**
     * @var Document
     */
    protected $document;

    /**
     * @var Product
     */
    protected $productDetails;

    /**
     * @var PriceParsers
     */
    protected $priceParser;

    /**
     * @var UserProductRequest
     */
    protected $userRequest;

    /**
     * AjaxDataUpdate constructor.
     *
     * @param Document           $document
     * @param Product            $productDetails
     * @param PriceParsers       $priceParser
     * @param UserProductRequest $userRequest
     */
    public function __construct(
        Document $document,
        Product $productDetails,
        PriceParsers $priceParser,
        UserProductRequest $userRequest
    ) {
        $this->document = $document;
        $this->productDetails = $productDetails;
        $this->priceParser = $priceParser;
        $this->userRequest = $userRequest;
    }

    public function updateProductPrice(Request $request)
    {
        $productUrl = strtok($request->input('productUrl'), '?');
        $sourceWeb = parse_url($productUrl, PHP_URL_HOST);
        $product = $this->getCurrentProduct($productUrl);
        $dom = $this->document->loadHtmlFile($productUrl);

        $this->productDetails = $product;
        $productId = $this->productDetails->id;

        if ($sourceWeb == 'www.1a.lv' || $sourceWeb == 'www.ksenukai.lv') {
            $price = $this->priceParser->parse1aPrice($dom, $productId);
        } elseif ($sourceWeb == 'www.rdveikals.lv') {
            $price = $this->priceParser->parseRdPrice($dom, $productId);
        } elseif ($sourceWeb == 'www.dateks.lv') {
            $price = $this->priceParser->parseDateksPrice($dom, $productId);
        }

        $this->userRequest->user_id = ($request->user()) ? $request->user()->id : 0;
        $this->userRequest->requested_product_id = $this->productDetails->id;
        $this->userRequest->save();

        $productData = [
            'productPrice' => $price->getAttribute('product_price'),
            'currency' => $price->getAttribute('currency'),
            'date' => $price->getAttributes()['created_at']
        ];

        return $productData;
    }

    public function getCurrentProduct($productUrl)
    {
        return $this->productDetails->where('product_url', $productUrl)->get()->first();
    }
}
