@section('pageTitle', $productMainData['product_name'])
@extends('layouts/app')
@section('page-class', 'product-page')
@section('content')
    <?php $productName = $productMainData['product_name'] ?>
    @if (session('status'))
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('status') }}</strong>
        </div>
    @endif
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
                <table class="table table-bordered product-info-table">
                    <thead>
                    <th><?= __('Datums') ?></th>
                    <th><?= __('Cena') ?></th>
                    </thead>
                    <tbody>
                    @foreach ($productPriceData as $priceData)
                        <?php $chartPrices[] = $priceData['product_price'] ?>
                        <?php $parseDates[] = '"' . $priceData['date'] . '"' ?>
                        <tr>
                            <td><?= $priceData['date'] ?></td>
                            <td><?= $priceData['product_price'] . $priceData['currency'] ?></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <canvas id="myChart" style="height: 30vw; width: 100vw;"></canvas>
        <script>
            var ctx = document.getElementById('myChart');
            new Chart(document.getElementById("myChart"), {
                "type": "line",
                "data": {
                    "labels": [<?= implode(', ', array_reverse($parseDates)) ?>],
                    "datasets": [{
                        "label": "<?= __('Cenu izmaiņas dinamika') ?>",
                        "data": [<?= implode(', ', array_reverse($chartPrices)) ?>],
                        "fill": false,
                        "borderColor": "#000",
                        "lineTension": 0.1
                    }]
                },
                "options": {}
            });
        </script>
    </div>
@endsection
