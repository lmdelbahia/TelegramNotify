<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('template/') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Telegram Notify') }}</title>
    <meta name="description" content="">
    <meta name="author" content="Miguel Alejandro González Antúnez">

    <link rel="icon" href="{{ url('favicon.ico') }}" type="image/x-icon">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('template/vendor/fonts/boxicons.css') }}">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/brands.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/solid.min.css') }}" rel="stylesheet">

    <!-- Template Styles -->
    <link rel="stylesheet" href="{{ asset('template/vendor/css/core.css') }}" class="template-customizer-core-css">
    <link rel="stylesheet" href="{{ asset('template/vendor/css/theme-default.css') }}" class="template-customizer-theme-css">
    <link rel="stylesheet" href="{{ asset('template/css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    @yield('extra-css')

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Template Scripts -->
    <script src="{{ asset('template/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/js/config.js') }}"></script>

    @yield('extra-js')

    <style>
        /* public-sans-regular - latin */
        @font-face {
            /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-display: swap;
            font-family: 'Public Sans';
            font-style: normal;
            font-weight: 400;
            src:
            /* Chrome 36+, Opera 23+, Firefox 39+ */
            url("{{ asset('fonts/public-sans-v14-latin-regular.woff2') }}") format('woff2'),

            /* Chrome 5+, Firefox 3.6+, IE 9+, Safari 5.1+ */
            url("{{ asset('fonts/public-sans-v14-latin-regular.woff') }}") format('woff');
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>