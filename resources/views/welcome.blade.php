@section('pageTitle','Homepage')
@extends('layouts/app')
@section('content')
    <div class="welcome-title-box">
        <h1>Welcome!</h1>
        <h1>Welcome!</h1>
    </div>
    <div id="example">
    </div>
    <script src="{{ asset('js/app.js') }}" defer>
        let test = 'Random string passing test';
    </script>
@endsection
