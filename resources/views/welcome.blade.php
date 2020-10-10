@section('pageTitle','Homepage')
@extends('layouts.app')
@section('page-class', 'homepage')
@section('content')
    <div class="context page-main-title">
        <h1><?= __('Laipni lūgti!') ?></h1>
        <div class="welcome-text">
            <span>
                <?= __('Jūs atrodaties TRLPRICE - iespējams, vienā no labākajām vietām, kur Jūs varat izsekot produktu cenas tirgū') ?>
            </span>
            <div class="welcome-start">
                <?= __('Dodieties') ?><a href="<?= route('check-product-price') ?>"><?= __(' šeit, ') ?></a>
                <?= __('lai sāktu izmantot to, kā dēļ Jūs tagad atrodaties šeit') ?> &#128526;
            </div>
        </div>
    </div>
    <div class="area">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
@endsection
