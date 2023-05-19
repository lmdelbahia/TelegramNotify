@extends('layouts.app')

@section('content_no_justify')

<main>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="{{ route('home') }}" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('template/img/logo.png') }}" alt="">
                                <span class="d-none d-lg-block">{{ config('app.name', 'Telegram Notify') }}</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">{{ __('Acceda al Sistema') }}</h5>
                                    <p class="text-center small">{{ __('Introdusca su correo y contraseña para acceder') }}</p>
                                </div>

                                <form action="{{ route('login') }}" method="POST" class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    <div class="col-12">
                                        <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-describedby="inputGroupPrepend">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">{{ __('Login') }}</button>
                                    </div>
                                    <div class="col-12">
                                        @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Olvidaste tu contraseña?') }}
                                        </a>
                                        @endif
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            <!-- All the links in the footer should remain intact. -->
                            <!-- You can delete the links only if you purchased the pro version. -->
                            <!-- Licensing information: https://bootstrapmade.com/license/ -->
                            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fa-solid fa-arrow-up"></i></a>

@endsection