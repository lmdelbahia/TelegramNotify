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
        <small class="text-muted">{{ __('Noticia') }}</small>
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
        <caption>{{ __('Listado de Noticias') }}</caption>
        <thead class="thead-dark">
            <tr>
                <th>{{ __('No.') }}</th>
                <th>{{ __('Título') }}</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="operationForm" name="operationForm" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="operationDialogTitle">{{ __('Noticia') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="titulo" name="titulo" maxlength="80" required placeholder="*">
                        <label for="titulo">{{ __('Título') }}</label>
                        <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                    </div>
                    <div class="form-group">
                        <label for="contenido">
                            {{ __('Contenido') }}
                            <i class="fas fa-question-circle text-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" data-bs-title="{{ __('Puede usar Markdown para el formato del contenido, ejemplo: ') }}<br/><strong>*bold text*</strong><br/><em>_italic text_</em><br/>[text](URL): <a href='#'>link<a/><br>`inline fixed-width code`<br/>```pre-formatted fixed-width code block```"></i>
                        </label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="8" required></textarea>
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

<!-- Modal Details -->
<div class="modal fade" id="detailsDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailsDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsDialogTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsDialogBody"></div>
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
    document.title = "{{ __('Gestionar Noticias') }}";
    var FORM_CREATE = true;
    //DataTable Object
    var table;

    $(function() {
        $("ul#administrar-nav").addClass("show");
        $("a#administrar-nav-noticia").addClass("active");
        //Spinners de carga
        $('#loadingContent').hide();
        $("#btnGuardarLoading").hide();
        $("#btnDeleteLoading").hide();
        $('[data-bs-toggle="tooltip"]').tooltip();

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
            ajax: "{{ route('noticia.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'titulo',
                    name: 'titulo'
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
        FORM_CREATE = true;
    }

    function fillDetails(data) {
        $('#detailsDialogTitle').html("{{ __('Detalles') }} - " + data.titulo);
        $('#detailsDialogBody').html(data.contenido);
        $('#detailsDialog').modal('show');
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
            url: "{{ route('noticia.index') }}" + '/' + $(element).data('id'),
            success: function(response) {
                $('#operationForm').autofill(response.data);
                if ($(element).data('type') === "edit") {
                    FORM_CREATE = false;
                    $('#operationDialog').modal('show');
                } else if ($(element).data('type') === "details") {
                    fillDetails(response.data);
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
            url: "{{ route('noticia.index') }}",
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
            url: "{{ route('noticia.index') }}" + '/' + $('#id').val(),
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
            url: "{{ route('noticia.index') }}" + '/' + $('#id').val(),
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