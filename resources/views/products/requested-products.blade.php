@section('pageTitle', 'Manis pieprasītie produkti')
@extends('layouts/app')
@section('content')
    <h1><?= __('Visi iepriekš pieprasītie produkti') ?></h1>
    <div class="all-products">
        @foreach ($products as $product)
            <div class="product-item">
                <img class="product-main-image" src="{{ asset('storage/' . $product->product_image_url) }}"
                     alt="product_image">
                <div class="product-name"><?= $product->product_name ?></div>
                <div class="link-to-product">
                    <a href="<?= $product->product_url ?>" target="_blank"><?= __('Links uz produktu') ?></a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
