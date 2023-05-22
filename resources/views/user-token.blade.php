@extends('layouts.app')

@section('extra-css')
<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('extra-js')
<script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid text-center">
    <h3>
        <small class="text-muted">{{ __('Tokens de Acceso a la API') }}</small>
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
        <caption>{{ __('Listado de Tokens de Acceso a la API') }}</caption>
        <thead class="thead-dark">
            <tr>
                <th>{{ __('No.') }}</th>
                <th>{{ __('Nombre') }}</th>
                <th>{{ __('Creado') }}</th>
                <th>{{ __('Último uso') }}</th>
                <th style="width: 64px">_________</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

<!-- Modal Operations -->
<div class="modal fade" id="operationDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="operationDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form id="operationForm" name="operationForm" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="operationDialogTitle">{{ __('Token de Acceso') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" maxlength="80" required placeholder="*">
                        <label for="name">{{ __('Nombre') }}</label>
                        <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="access_token" name="access_token" maxlength="255" placeholder="*" readonly>
                        <label for="name">{{ __('Token de Acceso') }}</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="Limpiar()">{{ __('Cerrar') }}</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <span class="spinner-border spinner-border-sm" role="status" id="btnGuardarLoading"></span>
                        {{ __('Generar') }}
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
                <button type="button" class="btn btn-danger" id="btnDelete" onclick="Async_Delete()">
                    <span class="spinner-border spinner-border-sm" role="status" id="btnDeleteLoading"></span>
                    {{ __('Eliminar') }}
                </button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    document.title = "{{ __('Gestionar Tokens de Acceso a la API') }}";
    //DataTable Object
    var table;

    $(function() {
        $("ul#administrar-nav").addClass("show");
        $("a#administrar-nav-user-token").addClass("active");
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
            ajax: "{{ route('user-token.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'last_used_at',
                    name: 'last_used_at'
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
    }

    function Async_Store() {
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
            url: "{{ route('user-token.index') }}",
            success: function(response) {
                $('#access_token').val(response.data.token);
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

    function Async_Delete(element) {
        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: "{{ route('user-token.index') }}" + '/' + $(element).data('id'),
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
                        Async_Store();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

@endsection