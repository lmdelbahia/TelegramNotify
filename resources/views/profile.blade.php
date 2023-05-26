@extends('layouts.app')

@section('extra-css')
@endsection

@section('extra-js')
<script type="text/javascript" src="{{ asset('js/jquery.formautofill.min.js') }}"></script>
@endsection

@section('content')
<section class="section profile">
    <div class="row">
        <div class="col-xl-4">

            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                    <img src="{{ asset('img/user-profile.png') }}" alt="Profile" class="rounded-circle">
                    <h2 id="profile-view-card-name">{{ $user['name'] }}</h2>
                    <h3>{{ $user['role'] }}</h3>
                </div>
            </div>

        </div>

        <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">{{ __('Resumen') }}</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">{{ __('Editar Perfil') }}</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">{{ __('Cambiar contraseña') }}</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">{{ __('Detalles del Perfil') }}</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">{{ __('Nombre Completo') }}</div>
                                <div class="col-lg-9 col-md-8" id="profile-view-summary-name">{{ $user['name'] }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">{{ __('Correo') }}</div>
                                <div class="col-lg-9 col-md-8">{{ $user['email'] }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">{{ __('Rol') }}</div>
                                <div class="col-lg-9 col-md-8">{{ $user['role'] }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">{{ __('Creado') }}</div>
                                <div class="col-lg-9 col-md-8">{{ $user['created_at'] }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">{{ __('Modificado') }}</div>
                                <div class="col-lg-9 col-md-8">{{ $user['updated_at'] }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">{{ __('Bots Registrados') }}</div>
                                <div class="col-lg-9 col-md-8">{{ $user['bots_count'] }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">{{ __('Noticias Registradas') }}</div>
                                <div class="col-lg-9 col-md-8">{{ $user['noticias_count'] }}</div>
                            </div>

                        </div>

                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                            <!-- Profile Edit Form -->
                            <form id="operationForm" name="operationForm" class="needs-validation" novalidate>

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-lg-3 col-form-label">{{ __('Nombre Completo') }}</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control" id="name" name="name" maxlength="255" value="{{ $user['name'] }}" required>
                                    </div>
                                    <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">{{ __('Correo') }}</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="email" class="form-control" id="email" name="email" maxlength="255" value="{{ $user['email'] }}" required>
                                    </div>
                                    <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                                        <span class="spinner-border spinner-border-sm" role="status" id="btnGuardarLoading"></span>{{ __('Guardar') }}
                                    </button>
                                </div>
                            </form><!-- End Profile Edit Form -->

                        </div>

                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <!-- Change Password Form -->
                            <form id="passwordForm" name="passwordForm" class="needs-validation" novalidate>

                                <div class="row mb-3">
                                    <label for="password_old" class="col-md-4 col-lg-3 col-form-label">{{ __('Contraseña actual') }}</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="password" class="form-control" id="password_old" name="password_old" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-lg-3 col-form-label">{{ __('Contraseña nueva') }}</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">{{ __(' Vuelva a escribir la contraseña nueva') }}</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="btnChangePasswd">
                                        <span class="spinner-border spinner-border-sm" role="status" id="btnChangePasswdLoading"></span>{{ __('Cambiar contraseña') }}
                                    </button>
                                </div>
                            </form><!-- End Change Password Form -->

                        </div>

                    </div><!-- End Bordered Tabs -->

                </div>
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">
    document.title = "{{ __('Perfil de Usuario') }}";

    $(function() {
        //Spinners de carga
        $("#btnGuardarLoading").hide();
        $("#btnChangePasswdLoading").hide();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })

    function Limpiar() {
        $('#id').val('');
        $('#passwordForm').trigger("reset");
        $('#passwordForm').removeClass("was-validated");
        $('#password_old').val('');
        $('#password').val('');
        $('#password_confirmation').val('');
    }

    function Async_Profile_Update() {
        $.ajax({
            data: $('#operationForm').serialize(),
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                $('#btnGuardar').attr("disabled", true);
                $("#btnGuardarLoading").show();
            },
            complete: function() {
                $('#btnGuardar').attr("disabled", false);
                $("#btnGuardarLoading").hide();
            },
            url: "{{ route('profile.update') }}",
            success: function(response) {
                AlertNotify("success", response.message);
                $('#app-menu-user-name').html($('#name').val());
                $('#profile-view-card-name').html($('#name').val());
                $('#profile-view-summary-name').html($('#name').val());
            },
            error: function(xhr) {
                if (xhr.responseJSON.errors) {
                    let errores = xhr.responseJSON.errors;
                    let messaje = "";
                    for (let key in errores) {
                        messaje += errores[key];
                    }
                    AlertNotify("error", messaje);
                } else {
                    AlertNotify("error", xhr.responseJSON.message);
                }
            }
        });
    }

    function Async_Password_Update() {
        $.ajax({
            data: $('#passwordForm').serialize(),
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                $('#btnChangePasswd').attr("disabled", true);
                $("#btnChangePasswdLoading").show();
            },
            complete: function() {
                $('#btnChangePasswd').attr("disabled", false);
                $("#btnChangePasswdLoading").hide();
            },
            url: "{{ route('profile.change-password') }}",
            success: function(response) {
                Limpiar();
                AlertNotify("success", response.message);
            },
            error: function(xhr) {
                if (xhr.responseJSON.errors) {
                    let errores = xhr.responseJSON.errors;
                    let messaje = "";
                    for (let key in errores) {
                        messaje += errores[key];
                    }
                    AlertNotify("error", messaje);
                } else {
                    AlertNotify("error", xhr.responseJSON.message);
                }
            }
        });
    }

    // Deshabilita Form Submit en casi de campo no válido
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                if (form.id == "operationForm") {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (form.checkValidity()) {
                            Async_Profile_Update();
                        }
                        form.classList.add('was-validated');
                    }, false);
                }
                if (form.id == "passwordForm") {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (form.checkValidity()) {
                            Async_Password_Update();
                        }
                        form.classList.add('was-validated');
                    }, false);
                }

            });
        }, false);
    })();
</script>

@endsection