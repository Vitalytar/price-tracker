<?php

namespace App\Http\Controllers;

use App\Product;

/**
 * Class ProductPageController
 *
 * @package App\Http\Controllers
 */
class ProductPageController extends Controller
{
    /**
     * Get data for product page
     *
     * @param int $productId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProduct($productId)
    {
        $productData = Product::where('id', $productId)->get();

        foreach ($productData as $product) {
            $productMainData = [
                'product_name' => $product->product_name,
                'product_url' => $product->product_url,
                'product_image_url' => $product->product_image_url,
                'source_web' => $product->source_web
            ];
        }

        $productPrices = Product::join('product_prices', 'product_details.id', '=', 'product_prices.product_relation_id')
            ->where('product_details.id', $productId)
            ->select('product_prices.*')
            ->orderBy('product_prices.created_at', 'desc')
            ->get();

        foreach ($productPrices as $productPrice) {
            $productPriceData[] = [
                'product_price' => $productPrice->product_price,
                'date' => $productPrice->getAttributes()['created_at'],
                'currency' => $productPrice->currency
            ];
        }

        return view('products/product-page')->with(
            ['productMainData' => $productMainData, 'productPriceData' => $productPriceData]
        );
    }
}
