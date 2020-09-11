@section('pageTitle', $productMainData['product_name'])
@extends('layouts/app')
@section('content')
    <div class="product-main-info">
        <h1><?= $productMainData['product_name'] ?></h1>
        <div class="product-data">
            <img class="product-main-image" src="{{ asset('storage/' . $productMainData['product_image_url']) }}"
                 alt="product_image">
            <table class="table table-striped product-info-table">
                <thead>
                <th><?= __('Datums') ?></th>
                <th><?= __('Cena') ?></th>
                </thead>
                <tbody>
                @foreach ($productPriceData as $priceData)
                    <tr>
                        <td><?= $priceData['date'] ?></td>
                        <td><?= $priceData['product_price'] . $priceData['currency'] ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
