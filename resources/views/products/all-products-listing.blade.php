@section('pageTitle', 'Visi produkti')
@extends('layouts/app')
@section('content')
    <h1><?= __('Visi produkti') ?></h1>
    <div class="all-products">
        @foreach ($products as $product)
            <div class="product-item">
                <img class="product-main-image" src="{{ asset('storage/' . $product->product_image_url) }}"
                     alt="product_image">
                <span class="product-name"><?= $product->product_name ?></span>
            </div>
        @endforeach
    </div>
@endsection
