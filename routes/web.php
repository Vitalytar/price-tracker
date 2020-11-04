<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// ==================== CONTROLLERS ====================
Route::post('/parse-product', 'ParseProductController@parse')->name('parse-product');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('product/{productId}/{productName}', ['uses' => 'ProductPageController@showProduct'])->name('product-page');
Route::get('/search', 'SearchController@searchResults')->name('search');
Route::post('/delete-requested-product', 'ProductActions\DeleteRequestedProduct@deleteRequestedProduct')->name('delete-requested-product');
Route::post('/ajax-product-price-update', 'ProductActions\AjaxDataUpdate@updateProductPrice')->name('update-product-price');
Route::post('/ajax-liked-product-save', 'ProductActions\LikeProduct@saveLikedProduct')->name('save-liked-product');

// ==================== PAGES ====================
Route::get('/check-product-price', function () {
    return view('products/check-product-price');
})->name('check-product-price');

Route::get('all-products', function () {
    $products = Product::inRandomOrder()->get();

    return view('products/all-products-listing', ['products' => $products]);
})->name('all-products');

Route::get('requested-products', function () {
    $products = DB::table('user_requested_product')
        ->join('product_details', 'user_requested_product.requested_product_id', '=', 'product_details.id')
        ->select(
            'user_requested_product.requested_product_id', 'user_requested_product.created_at',
            'product_details.product_name', 'product_details.product_url', 'product_details.product_image_url',
            'product_details.created_at', 'product_details.id', 'product_details.source_web'
        )
        ->where('user_requested_product.user_id', '=', Auth::user()->id)
        ->groupBy('user_requested_product.requested_product_id')
        ->orderByDesc('user_requested_product.created_at')
        ->get();

    return view('products/requested-products', ['products' => $products]);
})->name('requested-products');

Auth::routes();

