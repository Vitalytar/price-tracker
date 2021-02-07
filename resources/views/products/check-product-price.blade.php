@section('pageTitle','Pārbaudīt produkta cenu')
@extends('layouts/app')
@section('page-class', 'request-product')
@section('content')
    @if (session('warning'))
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('warning') }}</strong>
        </div>
    @endif
    <form class="product-request-form" method="POST" action="<?= route('parse-product') ?>">
        @csrf
        <label for="product-url">Ievadiet produkta linku lai dabūtu cenu izmaiņu grafiku</label>
            <input type="url" name="productUrl" minlength="3" required>
        <small>Piemērs: https://trlprice.com/links-uz-produktu</small>
        <button type="submit"><?= __('Pārbaudīt cenas vēsturi') ?></button>
    </form>
    <div class="parse-instruction-block">
        <h1 class="instruction-title">Kā tas strādā</h1>
        <div class="instruction-wrapper">
            <div class="instruction">
                <ol>
                    <li>Atrodiet interesējošo produktu vienā no pieejamajiem resursiem</li>
                    <li>Iekopējiet produkta linku laukā</li>
                    <li>Nospiediet uz pogu 'Pārbaudīt cenas vēsturi'</li>
                </ol>
            </div>
            <div class="available-resources">
                <h4>Atbalstītās vietnes</h4>
                <div class="available-resource">1a</div>
                <div class="available-resource">RD veikals</div>
                <div class="available-resource">Dateks</div>
                <div class="available-resource">Ksenukai</div>
                <div class="available-resource">Tet</div>
            </div>
        </div>
    </div>
@endsection
