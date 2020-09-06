<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Info</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
        .product-main-info {
            text-align: center;
        }
    </style>
</head>
<body>
@extends('layouts/app')
@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('status') }}</strong>
        </div>
    @endif
    @if (session('main_data'))
        <div class="product-main-info">
            <?php
            $productData = session('main_data');
            $priceData = session('price_data');
            ?>
            <h1><?= $productData['product_name'] ?></h1>
                <img class="product-main-image" src="{{ asset('storage/' . $productData['product_image']) }}"
                     alt="product_image">
                <table class="table table-striped product-info-table">
                <thead>
                    <th><?= __('Datums') ?></th>
                    <th><?= __('Cena') ?></th>
                </thead>
                <tbody>
                @foreach ($priceData as $price)
                    <tr>
                        <td><?= $price['date'] ?></td>
                        <td><?= $price['price'] . $price['currency'] ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <h3>How did you get here?</h3>
    @endif
@endsection
</body>
</html>
