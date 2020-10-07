<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="@yield('page-class', 'default')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', 'TRLPRICE')</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('js')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" class="logo" alt="logo"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link hvr-bounce-to-top" href="<?= route('check-product-price') ?>"><?= __('Pārbaudīt preci') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hvr-bounce-to-top" href="<?= route('all-products') ?>"><?= __('Visi produkti') ?></a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link hvr-bounce-to-top" href="<?= route('requested-products') ?>"><?= __('Pieprasītie produkti') ?></a>
                            </li>
                        @endauth
                    </ul>
                    <ul class="navbar-nav search">
                        <div class="search-box">
                            <form id="search-form" role="search" method="GET" action="{{ route('search') }}">
                                <input id="search-input" name="query" type="search" placeholder="<?= __('Meklēt...') ?>" autofocus required />
                                <button type="submit"><i class="fas fa-arrow-right"></i></button>
                            </form>
                        </div>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="page-content">
            @yield('content')
        </main>
    </div>
    @extends('components/footer')
</body>
</html>
