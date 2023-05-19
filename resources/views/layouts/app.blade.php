<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Telegram Notify') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Miguel Alejandro González Antúnez">

    <!-- Favicons -->
    <link href="{{ asset('template/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('template/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link rel="icon" href="{{ url('favicon.ico') }}" type="image/x-icon">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('vendor/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('vendor/fontawesome/css/brands.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('vendor/fontawesome/css/solid.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <!-- Template CSS File -->
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet" type="text/css" media="screen">

    @yield('extra-css')

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('template/js/main.js') }}"></script>

    @yield('extra-js')

    <script type="text/javascript">
        function AlertNotify(type, message) {
            Swal.fire({
                title: "<strong>{{ config('app.name', 'Telegram Notify') }}:</strong> ",
                text: message,
                icon: type,
                toast: true,
                position: 'bottom-end',
                timer: 5000,
                timerProgressBar: true,
            })
        }
    </script>

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Updated: Mar 09 2023 with Bootstrap v5.2.3
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->

</head>

<body>
    <x-layouts.base-fonts />

    @if (Auth::check())
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('template/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">{{ config('app.name', 'Telegram Notify') }}</span>
            </a>
            <i class="fa-solid fa-bars toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-tie"></i>
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>{{ optional(App\Helpers\FieldsOptions\RoleFieldOptions::tryFrom(Auth::user()->role))->label() }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="fa-solid fa-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="fa-solid fa-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="fa-solid fa-question-circle"></i>
                                <span>Need Help?</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-sign-out-alt"></i>
                                <span>{{ __('Salir') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link collapsed" href="index.html">
                    <i class="fa-solid fa-home-alt"></i>
                    <span>Home</span>
                </a>
            </li><!-- End Home Nav -->
            @if (auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#administrar-nav" data-bs-toggle="collapse" href="#">
                    <i class="fa-solid fa-user-shield"></i><span>Administrar</span><i class="fa-solid fa-chevron-down ms-auto"></i>
                </a>
                <ul id="administrar-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('user.index') }}" id="administrar-nav-user">
                            <i class="fa-solid fa-circle"></i><span>Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bot.index') }}" id="administrar-nav-bot">
                            <i class="fa-solid fa-circle"></i><span>Bots</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('noticia.index') }}" id="administrar-nav-noticia">
                            <i class="fa-solid fa-circle"></i><span>Noticias</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Administrar Nav -->
            @endif
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('docs') }}">
                    <i class="bi bi-file-earmark"></i>
                    <span>API Doc</span>
                </a>
            </li><!-- End Blank Page Nav -->
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>GoDjango</span></strong> &amp; <strong><span>NiceAdmin</span></strong>. Todos los derechos reservados
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            Diseñado por <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
        <div class="credits">
            Desarrollado por <a href="https://github.com/miguelalejandrog29">Miguel Alejandro González Antúnez</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fa-solid fa-arrow-up"></i></a>
    @endif
    @yield('content_no_justify')
</body>

</html>