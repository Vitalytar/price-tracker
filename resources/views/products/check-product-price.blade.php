@section('pageTitle','Pārbaudīt produkta cenu')
@extends('layouts/app')
@section('content')
    <form method="POST" action="<?= route('parse-product') ?>">
        @csrf
        <label>
            <span>Ievadiet produkta linku lai dabūtu cenu izmaiņu grafiku</span>
            <input type="url" name="product-url">
        </label>
        <input type="submit" value="<?= __('Pārbaudīt cenas vēsturi') ?>">
    </form>
@endsection
