@extends('layouts.app')

@section('extra-css')
<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('css/filepond.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('extra-js')
<script type="text/javascript" src="{{ asset('js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/filepond.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/filepond.jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/filepond-plugin-file-validate-type.min.js') }}"></script>
@endsection

@section('content')

<div class="align-content-start">
    <a class="btn btn-outline-dark btn-sm" href="{{ route('noticia.index') }}">{{ __('Atras') }}</a>
</div>
<div class="container-fluid text-center">
    <h3>
        <small class="text-muted">{{ __("Imágenes de la Noticia: {$noticia->titulo}") }}</small>
    </h3>
</div>

<div class="row">
    <div class="col"></div>
    <!-- center column -->
    <div class="col-md-8">
        <!-- general form elements -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Imagen') }}</h3>
            </div>
            <!-- /.card-header -->
            <form id="operationForm" name="operationForm" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="card-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="noticia_id" name="noticia_id" value="{{ $noticia->id }}">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="150" placeholder="*">
                                <label for="descripcion">{{ __('Descripción') }}</label>
                                <div class="invalid-feedback">{{ __('messages.required_field') }}</div>
                            </div>
                            <i class="fas fa-question-circle text-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" data-bs-title="{{ __('Puede usar Markdown para el formato del contenido, ejemplo: ') }}<br/><strong>*bold text*</strong><br/><em>_italic text_</em><br/>[text](URL): <a href='#'>link<a/><br>`inline fixed-width code`<br/>```pre-formatted fixed-width code block```"></i>

                        </div>
                        <div class="col">
                            <input type="file" id="imagen" name="imagen" multiple />
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">

                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
    <div class="col"></div>
</div>

<div class="container">
    <div class="container-fluid text-center" id="loadingContent">
        <div class="spinner-grow text-info" style="width: 4rem; height: 4rem;" role="status">
            <span class="sr-only">{{ __('Cargando...') }}</span>
        </div>
        <h5>
            <small class="text-muted">{{ __('Cargando...') }}</small>
        </h5>
    </div>

    <table id="dtContent" class="table table-striped table-bordered table-hover text-center shadow dt-responsive nowrap data-table">
        <caption>{{ __("Listado de Imágenes de la Noticia: {$noticia->titulo}") }}</caption>
        <thead class="thead-dark">
            <tr>
                <th>{{ __('No.') }}</th>
                <th>{{ __('Imágen') }}</th>
                <th>{{ __('Descripción') }}</th>
                <th>{{ __('Creado') }}</th>
                <th style="width: 128px">_____________</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

<!-- Modal Show -->
<div class="modal fade" id="showDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showDialogTitle">{{ __('Imágen') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="showDialogContent"></div>
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
                <button type="button" class="btn btn-danger" id="btnDelete" onclick="Async_Delete()">
                    <span class="spinner-border spinner-border-sm" role="status" id="btnDeleteLoading"></span>
                    {{ __('Eliminar') }}
                </button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    document.title = "{{ __('Gestionar Imágenes de la Noticia: {$noticia->titulo}') }}";
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
            ajax: "{{ route('noticia-imagen.index', ['noticia' => $noticia->id]) }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'imagen',
                    name: 'imagen',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'descripcion',
                    name: 'descripcion'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        //FilePond Config
        $.fn.filepond.registerPlugin(FilePondPluginFileValidateType);
        $.fn.filepond.setOptions({
            labelIdle: 'Arrastra y suelta tus archivos o <span class = "filepond--label-action"> Examinar <span>',
            labelInvalidField: "El campo contiene archivos inválidos",
            labelFileWaitingForSize: "Esperando tamaño",
            labelFileSizeNotAvailable: "Tamaño no disponible",
            labelFileLoading: "Cargando",
            labelFileLoadError: "Error durante la carga",
            labelFileProcessing: "Cargando",
            labelFileProcessingComplete: "Carga completa",
            labelFileProcessingAborted: "Carga cancelada",
            labelFileProcessingError: "Error durante la carga",
            labelFileProcessingRevertError: "Error durante la reversión",
            labelFileRemoveError: "Error durante la eliminación",
            labelTapToCancel: "toca para cancelar",
            labelTapToRetry: "tocar para volver a intentar",
            labelTapToUndo: "tocar para deshacer",
            labelButtonRemoveItem: "Eliminar",
            labelButtonAbortItemLoad: "Abortar",
            labelButtonRetryItemLoad: "Reintentar",
            labelButtonAbortItemProcessing: "Cancelar",
            labelButtonUndoItemProcessing: "Deshacer",
            labelButtonRetryItemProcessing: "Reintentar",
            labelButtonProcessItem: "Cargar",
            labelMaxFileSizeExceeded: "El archivo es demasiado grande",
            labelMaxFileSize: "El tamaño máximo del archivo es {filesize}",
            labelMaxTotalFileSizeExceeded: "Tamaño total máximo excedido",
            labelMaxTotalFileSize: "El tamaño total máximo del archivo es {filesize}",
            labelFileTypeNotAllowed: "Archivo de tipo no válido",
            fileValidateTypeLabelExpectedTypes: "Espera {allButLastType} o {lastType}",
            imageValidateSizeLabelFormatError: "Tipo de imagen no compatible",
            imageValidateSizeLabelImageSizeTooSmall: "La imagen es demasiado pequeña",
            imageValidateSizeLabelImageSizeTooBig: "La imagen es demasiado grande",
            imageValidateSizeLabelExpectedMinSize: "El tamaño mínimo es {minWidth} × {minHeight}",
            imageValidateSizeLabelExpectedMaxSize: "El tamaño máximo es {maxWidth} × {maxHeight}",
            imageValidateSizeLabelImageResolutionTooLow: "La resolución es demasiado baja",
            imageValidateSizeLabelImageResolutionTooHigh: "La resolución es demasiado alta",
            imageValidateSizeLabelExpectedMinResolution: "La resolución mínima es {minResolution}",
            imageValidateSizeLabelExpectedMaxResolution: "La resolución máxima es {maxResolution}",
        });
        // Turn input element into a pond with configuration options
        $('#imagen').filepond({
            allowMultiple: true,
            maxParallelUploads: 1,
            //name: 'imagen',
            // Only accept images
            acceptedFileTypes: ['image/*'],
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort) => {
                    // fieldName is the name of the input field
                    // file is the actual file object to send
                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);
                    formData.append("noticia_id", $('#noticia_id').val());
                    formData.append("descripcion", $('#descripcion').val());

                    const request = new XMLHttpRequest();
                    request.open('POST', "{{ route('noticia-imagen.store') }}");
                    request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'))
                    request.setRequestHeader('Accept', 'application/json')

                    // Should call the progress method to update the progress to 100% before calling load
                    // Setting computable to false switches the loading indicator to infinite mode
                    request.upload.onprogress = (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    };

                    // Should call the load method when done and pass the returned server file id
                    // this server file id is then used later on when reverting or restoring a file
                    // so your server knows which file to return without exposing that info to the client
                    request.onload = function() {
                        if (request.status >= 200 && request.status < 300) {
                            // the load method accepts either a string (id) or an object                            
                            load(request.responseText);
                        } else {
                            // Can call the error method if something is wrong, should exit after
                            AlertNotify("error", 'Algo salió mal');
                        }
                    };

                    request.send(formData);

                    // Should expose an abort method so the request can be cancelled
                    return {
                        abort: () => {
                            // This function is entered if the user has tapped the cancel button
                            request.abort();

                            // Let FilePond know the request has been cancelled
                            abort();
                        },
                    };
                },
                fetch: null,
                revert: null,
            },
            //instantUpload: false,
            onprocessfile: (error, file) => {
                if (error) {
                    AlertNotify("error", error);
                } else {
                    AlertNotify("success", "El archivo " + file.filename + " fue subido con éxito");
                    $('#imagen').filepond('removeFile', file.id);
                }
            },
            onprocessfiles: () => {
                table.draw();
            }
        });

    })

    function Async_Get(element) {
        $.ajax({
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#loadingContent').show();
            },
            complete: function() {
                $('#loadingContent').hide();
            },
            url: "{{ url('noticia-imagen') }}" + '/' + $(element).data('id'),
            success: function(response) {
                $('#id').val(response.data.id);
                if ($(element).data('type') === "delete") {
                    $('#deleteDialogTitle').html("{{ __('Eliminar') }} - " + response.data.id);
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

    function Async_Delete() {
        $.ajax({
            type: "DELETE",
            dataType: "json",
            url: "{{ route('noticia-imagen.store') }}" + '/' + $('#id').val(),
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

    function showImage(element) {
        $('#showDialogContent').html("<img src='" + $(element).data('url') + "' class='img-fluid'>");
        $('#showDialog').modal('show');
    }

    // Deshabilita Form Submit en casi de campo no válido
    /*(function() {
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
                        FORM_CREATE ? Async_Store() : Async_Update();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();*/
</script>

@endsection