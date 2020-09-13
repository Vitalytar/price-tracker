<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * Get products matching to search query. Searching by product name
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchResults(Request $request)
    {
        $searchRequest = $request->input('query');

        $matchingProducts = Product::where('product_name', 'like', '%' . $searchRequest . '%')->get();

        return view('products/search-results')->with(
            ['products' => $matchingProducts, 'searchRequest' => $searchRequest]
        );
    }
}
