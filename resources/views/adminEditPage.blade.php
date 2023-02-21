@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N   E D I T   P A G E -->

<iframe class="w-100 d-block overflow-hidden" src="<?php echo $data[0] ?>"  frameborder="0"></iframe>

<form id="editForm" class="d-none" action="{{url('admin/updatePage')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input name="page" value="<?php echo $data[1] ?>">
</form>

<div class="editSlideBar position-fixed w-100 bg-white-color">
    <h3 class="centerHorizontal d-inline-block pt-4"><strong>Editor</strong></h3>
    <div class="float-right p-3">
        <i title="Cancelar" id="adminCancel" class="adminIcon glyphicon glyphicon-remove white-color bg-blue-color rounded-circle interactive p-3 mr-lg-4 mr-2"></i>
        <i title="Guardar" id="adminSave" class="adminIcon glyphicon glyphicon-floppy-disk white-color bg-blue-color rounded-circle interactive p-3 mr-lg-4 mr-2"></i>
    </div>

</div>

<!-- A D M I N   A D D   C E R T I F I C A T E   M O D A L -->

<div class="modal" id="modalAdminUploadCertificate" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title centerHorizontal">Nuevo Certificado</h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="post" action="{{url('adminAddCertificate')}}" enctype="multipart/form-data">
                <div class="modal-body px-lg-5 px-3">
                    {{csrf_field()}}
                    <input type="hidden" id="certificate" name="certificate" required/>

                    <h5><small></small></h5>

                    <h6 class="mt-4">Título del certificado</h6>

                    <input type="text" id="certificateName" class="form-control" name="certificateName" placeholder="Título" required>

                    <h6 class="mt-4">Imagen del certificado</h6>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input interactive" id="uploadCertificateImg" accept="image/*" name="certificateImg" required>
                        <label class="custom-file-label" for="uploadCertificateImg" data-browse="Seleccionar Archivo">
                            <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Añadir certificado</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   A D D   C E R T I F I C A T E   M O D A L -->

<!-- A D M I N   E D I T   C E R T I F I C A T E   M O D A L -->

<div class="modal" id="modalAdminEditCertificate" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title centerHorizontal">Editar</h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="post" action="{{url('adminEditCertificate')}}" enctype="multipart/form-data">
                <div class="modal-body px-lg-5 px-3">
                    {{csrf_field()}}
                    <input type="hidden" id="editCertificate" name="certificate" required/>

                    <h6>Título del certificado</h6>

                    <input type="text" id="editCertificateName" class="form-control" name="certificateName" placeholder="Título">

                    <h6 class="mt-4">Imagen del certificado</h6>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input interactive" id="editCertificateImg" accept="image/*" name="certificateImg">
                        <label class="custom-file-label" for="editCertificateImg" data-browse="Seleccionar Archivo">
                            <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                        </label>
                    </div>

                    <h6 class="mt-4">Categoría</h6>

                    <select class="form-control" id="certificateCategory" name="category"></select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Editar certificado</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   E D I T   C E R T I F I C A T E   M O D A L -->

<!-- A D M I N   A D D   N E W   M O D A L -->

<div class="modal" id="modalAdminUploadNew" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-big h-100 my-0" role="document">
        <div class="modal-content mh-100 overflow-auto absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center">Nueva Entrada</h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newEntryForm" class="form-group px-lg-3 px-0" method="post" action="{{url('adminUploadNew')}}" data-edit="{{url('adminEditNew')}}" enctype="multipart/form-data">
                <input id="entryId" type="hidden" name="entryId">
                <div class="container">
                    {{csrf_field()}}
                    <div class="row">
                        <div class=" col-lg-6 col-12 pt-4">
                            <input class="form-control text-center" type="text" placeholder="Título" name="entryTitle" required>
                        </div>
                        <div class=" col-lg-6 col-12 pt-4">
                            <input class="form-control text-center" type="text" placeholder="Categoría" name="entryCategory" required>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="editorContent col-lg-6 col-12 pt-4 pb-4">
                            <div id="blogEditor" class="interactive"></div>
                            <input id="entryContent" type="hidden" name="entryContent">
                        </div>
                        <div class="editorContent col-lg-6 col-12 pt-4 pb-4">
                            <img id="adminNewsAddPhoto" class="h-100 w-100 interactive"  alt="Añadir foto para esta entrada." src="{{asset('images/adminAddPhoto.svg')}}" data-remember="{{asset('images/adminAddPhoto.svg')}}">
                            <input id="adminNewPhoto" type="file" class="d-none" name="entryImage" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Crear Entrada</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   A D D   N E W   M O D A L -->

<!-- A D M I N   A D D   B O O K   M O D A L -->

<div class="modal" id="modalAdminUploadBook" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center">Nuevo Libro</h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group addBookForm" method="post" data-add="{{url('adminAddBook')}}" data-edit="{{url('adminEditBook')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="oldTitle" name="oldTitle" disabled>
                <div class="modal-body px-lg-5 px-3">
                    <div class="row newBookTab mb-3">
                        <div class="col-lg-3 col-6 selected">
                            <h6 class="text-center interactive pt-1"><strong>General</strong></h6>
                        </div>
                        <div class="col-lg-3 col-6">
                            <h6 class="text-center interactive pt-1"><strong>Específico</strong></h6>
                        </div>
                        <div class="col-lg-3 col-6">
                            <h6 class="text-center interactive pt-1"><strong>Venta</strong></h6>
                        </div>
                        <div class="col-lg-3 col-6">
                            <h6 class="text-center interactive pt-1"><strong>Avanzado</strong></h6>
                        </div>
                    </div>
                    <div class="row newBookTabValues">
                        <div class="col-12">

                            <h6 class="mt-2">Título*</h6>

                            <input type="text" class="form-control" data-title="title" name="bookTitle" required>

                            <h6 class="mt-2">Autor*</h6>

                            <input type="text" class="form-control" data-title="author" name="bookAuthor" required>

                            <h6 class="mt-2">ISBN*</h6>

                            <input type="text" class="form-control" data-title="isbn" name="bookIsbn" required>

                            <h6 class="mt-2">Descripción</h6>

                            <textarea class="form-control" name="bookDescription" data-title="description" rows="4"></textarea>

                        </div>
                        <div class="col-12 d-none">

                            <h6 class="mt-2">Medidas</h6>

                            <input type="text" class="form-control" name="bookMeasures" data-title="measures">

                            <h6 class="mt-2">Número de páginas</h6>

                            <input type="number" class="form-control" name="bookPageNumber" min="0" data-title="pages">

                            <h6 class="mt-2">Idioma</h6>

                            <input type="text" class="form-control" name="bookLanguage" data-title="language">

                            <h6 class="mt-2">Encuadernado</h6>

                            <input type="text" class="form-control" name="bookBinding" data-title="bookbinding">

                            <h6 class="mt-2">Edición</h6>

                            <input type="text" class="form-control" name="bookEdition" data-title="edition">

                        </div>
                        <div class="col-12 d-none">

                            <h6 class="mt-2">Precio en físico*</h6>

                            <input type="number" class="form-control" name="bookPhysicalPrice" data-title="physicalPrice" min="0" step="0.01" required>

                            <h6 class="mt-2">Precio en digital*</h6>

                            <input type="number" class="form-control" name="bookDigitalPrice" data-title="digitalPrice" min="0" step="0.01" required>

                            <h6 class="mt-2">Stock*</h6>

                            <input type="number" class="form-control" name="bookStock" data-title="stock" min="0" required>

                            <h6 class="mt-2">Descuento</h6>

                            <input type="number" class="form-control" name="bookDiscount" data-title="discount" min="0">

                            <div class="row mt-3">
                                <div class="col-4 mt-4">
                                    <h6 >Promocionar</h6>
                                </div>
                                <div class="col-4 mt-4 ml-2">
                                    <input type="checkbox" class="form-check-input interactive" name="bookPromote" data-title="promoted">
                                </div>
                            </div>


                        </div>
                        <div class="col-12 d-none">

                            <h6 class="mt-2">Certificados*</h6>

                            <div class="form-control scrollAutoBox" id="additionalCertificates"></div>

                            <h6 class="mt-2">Portada*</h6>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input interactive" id="uploadBookImg" accept="image/*" name="bookImage" required>
                                <label class="custom-file-label" for="uploadBookImg" data-browse="Seleccionar Archivo">
                                    <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                                </label>
                            </div>

                            <h6 class="mt-2">Libro (PDF)*</h6>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input interactive" id="uploadBookPdf" accept="application/pdf" name="bookFile" required>
                                <label class="custom-file-label" for="uploadBookPdf" data-browse="Seleccionar Archivo">
                                    <p class="w-50 text-overflow-ellipsis">Sube tu PDF</p>
                                </label>
                            </div>
                        </div>
                        <div class="col-12 uploadingFiles d-none">

                            <h6 class="mt-2">Subiendo archivos</h6>

                            <h6 class="my-1 progressInfo"></h6>

                            <svg width="100%" height="80%" xmlns="http://www.w3.org/2000/svg" viewBox="-200 -200 500 500" preserveAspectRatio="xMidYMid" class="lds-rolling">
                                <circle cx="50" cy="50" fill="none"   stroke="#0096AF" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138" transform="rotate(188.805 50 50)">
                                    <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="2s" begin="0s" repeatCount="indefinite"></animateTransform>
                                </circle>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Añadir libro</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   A D D   B O O K   M O D A L -->

<!-- A D M I N   D E L E T E   B O O K   M O D A L -->

<div class="modal" id="modalAdminDeleteBook" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center"></h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="post" action="{{url('adminDeleteBook')}}">
                {{csrf_field()}}
                <input type="hidden" id="deleteBook" name="deleteBook" required/>
                <div class="modal-body px-lg-5 px-3"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Borrar libro</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   D E L E T E   B O O K   M O D A L -->

<!-- A D M I N   D E L E T E   N E W   M O D A L -->

<div class="modal" id="modalAdminDeleteNew" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center"></h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="post" action="{{url('adminDeleteNew')}}">
                {{csrf_field()}}
                <input type="hidden" id="deleteNew" name="deleteNew" required/>
                <div class="modal-body px-lg-5 px-3"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Borrar Entrada</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   D E L E T E   N E W   M O D A L -->

<script type="text/javascript" id="allAdditionalDataScript">
    let allCertificates = [];
    let allBooks;

    <?php

    if(isset($data[2])){
        foreach($data[2] as $currentCertificate){
            echo 'allCertificates.push("'.$currentCertificate['certificate'].'");';
        }
    }

    if(isset($data[3])){
        echo 'allBooks = '.$data[3];
    }

    ?>

    $(window).on("load", function () {
        $('#allAdditionalDataScript').remove();
    });
</script>

<!-- E N D   A D M I N   E D I T   P A G E -->

<script type="text/javascript" src="{{asset('js/editPage.min.js')}}"></script>

@stop
@section('footer')
