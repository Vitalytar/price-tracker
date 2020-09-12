@section('pageTitle', 'Manis pieprasītie produkti')
@extends('layouts/app')
@section('js')
    <script src="{{ asset('js/layout-switcher.js') }}"></script>
@endsection
@section('content')
    <?php
    $escapeLatvian = [
        'Ā' => 'a', 'ā' => 'a', 'Č' => 'c', 'č' => 'c', 'Ē' => 'e', 'ē' => 'e', 'Ģ' => 'g', 'ģ' => 'g', 'Ī' => 'i',
        'ī' => 'i', 'Ķ' => 'k', 'ķ' => 'k', 'Ļ' => 'l', 'ļ' => 'l', 'Ņ' => 'n', 'ņ' => 'n', 'Š' => 's', 'š' => 's',
        'Ū' => 'ū', 'ū' => 'u', 'Ž' => 'z', 'ž' => 'z'
    ]
    ?>
    @if ($products->count() > 0)
        <h1><?= __('Visi iepriekš pieprasītie produkti') ?></h1>
        <div class="change-product-view">
            <i class="fas fa-th fa-2x grid-icon active" onclick="gridView()"></i>
            <i class="fas fa-list fa-2x list-icon disabled" onclick="listView()"></i>
        </div>
        <div class="all-products grid-view">
            @foreach ($products as $product)
                <div class="product-item">
                    <a href="<?= route(
                        'product-page', [
                        'productName' => strtr(strtolower(str_replace([' ', ',', '/'], '-', $product->product_name)), $escapeLatvian),
                        'productId' => $product->id
                    ]
                    ) ?>" class="link-to-product-page">
                        <img class="product-main-image" src="{{ asset('storage/' . $product->product_image_url) }}"
                             alt="product_image">
                        <div class="product-name"><?= $product->product_name ?></div>
                        <div class="link-to-product-source">
                            <a href="<?= $product->product_url ?>" target="_blank"><?= __('Avots: ') . $product->source_web ?></a>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <h1><?= __('Jūs neesat meklējis/-usi produktus') ?></h1>
    @endif
@endsection
