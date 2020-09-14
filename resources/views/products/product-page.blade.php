@section('pageTitle', $productMainData['product_name'])
@extends('layouts/app')
@section('content')
    <?php $productName = $productMainData['product_name'] ?>
    <div class="product-main-info">
        <div class="product-page-title">
            <?= $productName ?>
            <div><?= $productName ?></div>
            <div><?= $productName ?></div>
            <div><?= $productName ?></div>
            <div><?= $productName ?></div>
        </div>
        <div class="product-details">
            <img class="product-main-image" src="{{ asset('storage/' . $productMainData['product_image_url']) }}"
                 alt="product_image">
            <div class="product-detailed-info">
                <div class="product-actions">
                    <div class="link-to-product">
                        <a href="<?= $productMainData['product_url'] ?>" target="_blank"><?= __('Apskatīt produktu') ?></a>
                    </div>
                    <div class="actual-price-parse">
                        <form method="POST" action="<?= route('parse-product') ?>">
                            @csrf
                            <input name="product-url" value="<?= $productMainData['product_url'] ?>" type="hidden">
                            <button type="submit"><?= __('Saņemt aktuālo cenu') ?></button>
                        </form>
                    </div>
                </div>
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
    </div>
@endsection
