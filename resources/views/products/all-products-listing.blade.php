@section('pageTitle', 'Visi produkti')
@extends('layouts/app')
@section('content')
    <?php
    $escapeLatvian = [
        'Ā' => 'a', 'ā' => 'a', 'Č' => 'c', 'č' => 'c', 'Ē' => 'e', 'ē' => 'e', 'Ģ' => 'g', 'ģ' => 'g', 'Ī' => 'i',
        'ī' => 'i', 'Ķ' => 'k', 'ķ' => 'k', 'Ļ' => 'l', 'ļ' => 'l', 'Ņ' => 'n', 'ņ' => 'n', 'Š' => 's', 'š' => 's',
        'Ū' => 'ū', 'ū' => 'u', 'Ž' => 'z', 'ž' => 'z'
    ]
    ?>
    <h1><?= __('Visi produkti') ?></h1>
    <div class="all-products">
        @foreach ($products as $product)
            <div class="product-item">
                <a href="<?= route('product-page', [
                    'productName' => strtr(strtolower(str_replace([' ', ','], '-', $product->product_name)), $escapeLatvian),
                    'productId' => $product->id
                ]) ?>">
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
