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
        <small class="text-muted">{{ __('Bots de Telegram') }}</small>
    </h3>
    <small class="text-body-secondary">{{ __('Puede crear un Bot solicitándoselo a') }} <a href="https://t.me/BotFather" target="__blank">@BotFather</a> </small>
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
        <caption>{{ __('Listado de Bots de Telegram') }}</caption>
        <thead class="thead-dark">
            <tr>
                <th>{{ __('No.') }}</th>
                <th>{{ __('Usuario') }}</th>
                <th>{{ __('Nombre') }}</th>
                <th>{{ __('Token') }}</th>
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
                    <h5 class="modal-title" id="operationDialogTitle">{{ __('Bot') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group mb-3">
                        <label for="user_id">{{ __('Usuario') }}</label>
                        <select class="form-select" id="user_id" name="user_id">
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" maxlength="50" required placeholder="*">
                        <label for="name">{{ __('Nombre') }}</label>
                        <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="token" name="token" maxlength="255" required placeholder="*">
                        <label for="token">{{ __('Token') }}</label>
                        <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
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
    document.title = "{{ __('Gestionar Bots de Telegram') }}";
    var FORM_CREATE = true;
    //DataTable Object
    var table;

    $(function() {
        $("ul#administrar-nav").addClass("show");
        $("a#administrar-nav-bot").addClass("active");
        //Spinners de carga
        $('#loadingContent').hide();
        $("#btnGuardarLoading").hide();
        $("#btnDeleteLoading").hide();

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
            ajax: "{{ route('bot.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'token',
                    name: 'token'
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
        FORM_CREATE = true;
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
            url: "{{ route('bot.index') }}" + '/' + $(element).data('id'),
            success: function(response) {
                $('#operationForm').autofill(response.data);
                if ($(element).data('type') === "edit") {
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
            url: "{{ route('bot.index') }}",
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
            url: "{{ route('bot.index') }}" + '/' + $('#id').val(),
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
            url: "{{ route('bot.index') }}" + '/' + $('#id').val(),
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