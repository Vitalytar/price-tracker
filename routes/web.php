<?php

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

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/parse-product', 'ParseProductController@parse')->name('parse-product');
Route::get('/product-info', function () {
    return view('products/product-info');
})->name('product-info');
Route::get('all-products', function() {
    $products = Product::inRandomOrder()->get();

    return view('products/all-products-listing', ['products' => $products]);
})->name('all-products');
