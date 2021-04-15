@section('pageTitle', 'Mans profils')
@extends('layouts/app')
@section('page-class', 'my-account')
@section('js')
@endsection
@section('content')
    <?php $user = Auth::user() ?>
    <h1><?= __('Profils') ?></h1>
    <x-user-account parsedTimes="" userName="" userEmail="" accountCreationTime=""/>
@endsection
