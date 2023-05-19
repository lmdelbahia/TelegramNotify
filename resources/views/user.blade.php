@extends('layouts.app')

@section('extra-css')
<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('extra-js')
<script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.formautofill.min.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid text-center">
    <h3>
        <small class="text-muted">{{ __('Usuario') }}</small>
    </h3>
</div>

<div class="container">
    <div class="w-85 pb-1 mb-3">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success shadow" data-bs-toggle="modal" data-bs-target="#operationDialog" onclick="Limpiar()">
            <i class="fas fa-plus"></i> {{ __('Nuevo') }}
        </button>
    </div>
    <div class="container-fluid text-center" id="loadingContent">
        <div class="spinner-grow text-info" style="width: 4rem; height: 4rem;" role="status">
            <span class="sr-only">{{ __('Cargando...') }}</span>
        </div>
        <h5>
            <small class="text-muted">{{ __('Cargando...') }}</small>
        </h5>
    </div>

    <table id="dtContent" class="table table-striped table-bordered table-hover text-center shadow dt-responsive nowrap data-table">
        <caption>{{ __('Listado de Usuarios') }}</caption>
        <thead class="thead-dark">
            <tr>
                <th>{{ __('No.') }}</th>
                <th>{{ __('Nombre') }}</th>
                <th>{{ __('Correo') }}</th>
                <th>{{ __('Rol') }}</th>
                <th>{{ __('Creado') }}</th>
                <th>{{ __('Actualizado') }}</th>
                <th style="width: 128px">_____________</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

<!-- Modal Operations -->
<div class="modal fade" id="operationDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="operationDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="operationForm" name="operationForm" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="operationDialogTitle">{{ __('Usuario') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" maxlength="255" required placeholder="*">
                        <label for="name">{{ __('Nombre') }}</label>
                        <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" maxlength="255" required placeholder="*">
                        <label for="email">{{ __('Correo') }}</label>
                        <div class="invalid-feedback">{{ __('messages.required_field') . ' ' . __('messages.email_field') }}</div>
                    </div>
                    <div class="form-check form-switch my-3" id="fcbxChangePasswd">
                        <input class="form-check-input" type="checkbox" id="cbxChangePassword" name="cbxChangePassword" onchange="togglePasswordFields()">
                        <label class="form-check-label" for="cbxChangePassword">{{ __('Cambiar la Contraseña') }}</label>
                    </div>
                    <div id="passwordFields">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" maxlength="255" placeholder="*">
                            <label for="password">{{ __('Contraseña') }}</label>
                            <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" maxlength="255" placeholder="*">
                            <label for="password_confirmation">{{ __('Confirmar Contraseña') }}</label>
                            <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role">{{ __('Rol') }}</label>
                        <select class="form-select" id="role" name="role">
                            @foreach ($roles as $role)
                            <option value="{{ $role['value'] }}">{{ $role['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <span class="spinner-border spinner-border-sm" role="status" id="btnGuardarLoading"></span>
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Show -->
<div class="modal fade" id="showDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showDialogTitle">{{ __('Token de Acceso') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="showDialogContent">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" id="access_token" name="access_token" readonly>
                    </div>
                    <button type="button" class="btn btn-dark" id="btnGenerate" onclick="Asinc_Generate_Token()">
                        <span class="spinner-border spinner-border-sm" role="status" id="btnGenerateLoading"></span>
                        {{ __('Generar') }}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDialogTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-danger" id="btnDelete" onclick="Asinc_Delete()">
                    <span class="spinner-border spinner-border-sm" role="status" id="btnDeleteLoading"></span>
                    {{ __('Eliminar') }}
                </button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    document.title = "{{ __('Gestionar Usuarios') }}";
    var FORM_CREATE = true;
    //DataTable Object
    var table;

    $(function() {
        $("ul#administrar-nav").addClass("show");
        $("a#administrar-nav-user").addClass("active");
        //Spinners de carga
        $('#loadingContent').hide();
        $("#btnGuardarLoading").hide();
        $("#btnDeleteLoading").hide();
        $("#btnGenerateLoading").hide();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        table = $('#dtContent').DataTable({
            processing: true,
            serverSide: true,
            fixedHeader: true,
            language: {
                url: "{{ asset('i18n/es-ES.DataTables.json') }}",
            },
            ajax: "{{ route('user.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    })

    function Limpiar() {
        $('#id').val('');
        $('#operationForm').trigger("reset");
        $('#operationForm').removeClass("was-validated");
        $('#password').prop('required', true);
        $('#password_confirmation').prop('required', true);
        $('#fcbxChangePasswd').hide();
        $('#cbxChangePassword').prop('checked', 1);
        $('#passwordFields').slideDown();
        FORM_CREATE = true;
        $('#access_token').val('');
    }

    function togglePasswordFields() {
        if ($('#cbxChangePassword').prop('checked')) {
            $('#passwordFields').slideDown();
            $('#password').prop('required', true);
            $('#password_confirmation').prop('required', true);
        } else {
            $('#password').prop('required', false);
            $('#password_confirmation').prop('required', false);
            $('#passwordFields').slideUp();
        }
    }

    function Asinc_Get(element) {
        Limpiar();
        $.ajax({
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#loadingContent').show();
            },
            complete: function() {
                $('#loadingContent').hide();
            },
            url: "{{ route('user.index') }}" + '/' + $(element).data('id'),
            success: function(response) {
                $('#operationForm').autofill(response.data);
                if ($(element).data('type') === "edit") {
                    $('#fcbxChangePasswd').show();
                    $('#cbxChangePassword').prop('checked', 0);
                    $('#passwordFields').slideUp();
                    $('#password').prop('required', false);
                    $('#password_confirmation').prop('required', false);
                    FORM_CREATE = false;
                    $('#operationDialog').modal('show');
                } else {
                    $('#deleteDialogTitle').html("{{ __('Eliminar') }} - " + response.data.name);
                    $('#deleteDialog').modal('show');
                }
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
                if (xhr.status == 404) {
                    table.draw();
                }
            }
        });
    }

    function Asinc_Store() {
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
            url: "{{ route('user.index') }}",
            success: function(response) {
                $('#operationDialog').modal('hide');
                Limpiar();
                AlertNotify("success", response.message);
                table.draw();
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
                if (xhr.status == 404) {
                    table.draw();
                }
            }
        });
    }

    function Asinc_Update() {
        $.ajax({
            data: $('#operationForm').serialize(),
            type: "PUT",
            dataType: "json",
            beforeSend: function() {
                $('#btnGuardar').attr("disabled", true);
                $("#btnGuardarLoading").show();
            },
            complete: function() {
                $('#btnGuardar').attr("disabled", false);
                $("#btnGuardarLoading").hide();
            },
            url: "{{ route('user.index') }}" + '/' + $('#id').val(),
            success: function(response) {
                $('#operationDialog').modal('hide');
                Limpiar();
                AlertNotify("success", response.message);
                table.draw();
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
                if (xhr.status == 404) {
                    table.draw();
                }
            }
        });
    }

    function Asinc_Delete() {
        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: "{{ route('user.index') }}" + '/' + $('#id').val(),
            beforeSend: function() {
                $('#btnDelete').attr("disabled", true);
                $("#btnDeleteLoading").show();
            },
            complete: function() {
                $('#btnDelete').attr("disabled", false);
                $("#btnDeleteLoading").hide();
            },
            success: function(data) {
                $('#deleteDialog').modal('hide');
                Limpiar();
                AlertNotify("success", data.message);
                table.draw();
            },
            error: function(xhr) {
                if (xhr.responseJSON.errors) {
                    let errores = xhr.responseJSON.errors;
                    let message = "";
                    for (let key in errores) {
                        message += errores[key];
                    }
                    AlertNotify("error", message);
                } else {
                    AlertNotify("error", xhr.responseJSON.message);
                }
                if (xhr.status == 404) {
                    table.draw();
                }
            }
        });
    }

    function Show_Token_Dialog(element) {
        Limpiar();
        $('#id').val($(element).data('id'));
        $('#showDialog').modal('show');
    }

    function Asinc_Generate_Token() {
        $.ajax({
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#btnGenerate').attr("disabled", true);
                $("#btnGenerateLoading").show();
            },
            complete: function() {
                $('#btnGenerate').attr("disabled", false);
                $("#btnGenerateLoading").hide();
            },
            url: "{{ route('user.index') }}" + '/generate-token/' + $('#id').val(),
            success: function(response) {
                $('#access_token').val(response.data.token);
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
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (form.checkValidity()) {
                        FORM_CREATE ? Asinc_Store() : Asinc_Update();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

@endsection