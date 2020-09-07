@section('pageTitle','Homepage')
@extends('layouts/app')
@section('content')
    <div id="example">
    </div>
    <script src="{{ asset('js/app.js') }}" defer>
        let test = 'Random string passing test';
    </script>
@endsection
