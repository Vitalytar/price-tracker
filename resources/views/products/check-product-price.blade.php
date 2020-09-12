@section('pageTitle','Pārbaudīt produkta cenu')
@extends('layouts/app')
@section('content')
    @if (session('warning'))
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ session('warning') }}</strong>
        </div>
    @endif
    <form method="POST" action="<?= route('parse-product') ?>">
        @csrf
        <label for="product-url">Ievadiet produkta linku lai dabūtu cenu izmaiņu grafiku</label>
            <input type="url" name="product-url" minlength="3" required>
        <button type="submit"><?= __('Pārbaudīt cenas vēsturi') ?></button>
    </form>
@endsection
