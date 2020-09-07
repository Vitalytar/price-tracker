@section('pageTitle', 'Visi produkti')
@extends('layouts/app')
@section('content')
    <h1><?= __('Visi produkti') ?></h1>
    <div class="all-products">
        @foreach ($products as $product)
            <div class="product-item">
                <a href="<?= route('product-page', ['product-name' => strtolower(str_replace([' ', ','], '-', $product->product_name))]) ?>">
                    <img class="product-main-image" src="{{ asset('storage/' . $product->product_image_url) }}"
                         alt="product_image">
                    <div class="product-name"><?= $product->product_name ?></div>
                    <div class="link-to-product">
                        <a href="<?= $product->product_url ?>" target="_blank"><?= __('Links uz produktu') ?></a>
                        <div class="product-source"><?= __('Avots: ') . $product->source_web ?></div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
