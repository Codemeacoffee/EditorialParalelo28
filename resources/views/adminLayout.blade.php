@section('header')
    <!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <title>Paralelo28 - Admin</title>
    <meta name="author" content="Inversiones Borma S.L.">
    <meta name="keywords" content="Editorial, Paralelo, 28,Paralelo28, Formación, Educación, Empleo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/glyphicon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrapAdaptations.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/navBar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/lateralMenu.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/shoppingCart.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/catalogue.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bookViewer.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/footer.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/utils.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/effects.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/admin.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/paralelo28/style.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('js/jquery-ui-1.12.1/jquery-ui.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/quilljsSnow.min.css')}}">
    <link rel="shortcut icon" type="image/png" href= "{{asset('images/paralelo28favicon-admin.png')}}"/>
    <link rel="preload" href="{{asset('images/loadingAdmin.svg')}}" as="image">
    <script type="text/javascript"  src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript"  src="{{asset('js/jquery-ui-1.12.1/jquery-ui.min.js')}}" defer></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/quilljs.min.js')}}" defer></script>
    <script type="text/javascript"  src="{{asset('js/swiper.min.js')}}" defer></script>
    <script type="text/javascript"  src="{{asset('js/cookies.min.js')}}" defer></script>
    <script type="text/javascript"  src="{{asset('js/chart.min.js')}}" defer></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-143758482-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-143758482-1');
    </script>
</head>
<body>

<?php

//User session scripts

  if($userData){
        echo'<script id="sessionScript" type="text/javascript">let session = true; let physicalTax = '.$userData["taxPhysical"].'; let digitalTax = '.$userData["taxDigital"].';</script>';
    }else{
        echo'<script id="sessionScript" type="text/javascript">let physicalTax = '.$defaultPhysicalTax.'; let digitalTax = '.$defaultDigitalTax.'</script>';
    }

//Reload in a specific page scripts

if(isset($data[2])){
    if($data[2] == 'statistics'){
        echo
        '<script id="reloadScript" type="text/javascript">
        $(window).on("load", function() {
           arrayAddClass(controlPanelOptions, "grey-color");
           arrayAddClass(contentBlocks, "displayNone");
           statistics.removeClass("displayNone");
           $(controlPanelOptions[1]).removeClass("grey-color");
           $("#reloadScript").remove();
        })
       </script>';
    }
}

?>

<!-- N O   S C R I P T -->

<noscript style="background-image: url({{asset('images/press.jpg')}});">
    <div class="container-fluid">
        <div class="col-xl-6 col-lg-8 col-md-10 col-12 absoluteCenterBoth bg-slateWhite-color rounded shadow py-5 px-xl-5 px-lg-5 px-md-5 px-3">
            <img alt="Logo de la Editorial Paralelo28" class="centerHorizontal" src="{{asset('images/paraleloLogo.png')}}">
            <h5 class="mt-4">EditorialParalelo28.com necesita Javascript para poder operar correctamente, por ello, le pedimos que active
                Javascript para poder acceder a la web. <br><br> Esto lo puede hacer desde los siguientes enlaces segun su navegador:
            </h5>
            <div class="row mt-4">
                <div class="col-xl-3 col-lg-3 col-6">
                    <ul class="list-group">
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/chrome" target="_blank">Chrome</a></li>
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/safari-iphone" target="_blank">Safari (iphone)</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-3 col-6">
                    <ul class="list-group">
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/firefox" target="_blank">Firefox</a></li>
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/safari-ipad" target="_blank">Safari (ipad)</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-3 col-6">
                    <ul class="list-group">
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/opera" target="_blank">Opera</a></li>
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/edge" target="_blank">Edge</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-3 col-6">
                    <ul class="list-group">
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/safari" target="_blank">Safari</a></li>
                        <li class="list-group-item bg-slateWhite-color border-0"><a href="https://www.whatismybrowser.com/guides/how-to-enable-javascript/internet-explorer" target="_blank">Internet Explorer</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</noscript>

<!-- E N D   N O   S C R I P T -->

<!-- L O A D I N G   L A Y E R -->

<div class="loadingLayer">
    <div class="position-relative h-100">
        <img width="25%" height="25%" class="absoluteCenterBoth" alt="Icono de carga" src="{{asset('images/loadingAdmin.svg')}}">
    </div>
</div>
<script id="loadingScript" type="text/javascript">
    $(window).on("load", function () {
        $('.loadingLayer').fadeOut("slow");
        $('#loadingScript').remove();
    });
</script>

<!-- E N D   L O A D I N G   L A Y E R  -->

<!-- C O O K I E S   P O P   U P -->

<div id="cookie_directive_container" class="container position-fixed cookies bg-slateGrey-color rounded shadow">
    <nav class="navbar navbar-default navbar-fixed-bottom">

        <div class="container">
            <div class="navbar-inner navbar-content-center" id="cookie_accept">

                <a class="btn btn-default w-100 acceptCookies"><h5 class="theX interactive float-right hoverRed closeUserAccess" data-dismiss="modal" aria-hidden="true">×</h5></a>
                <p class="text-muted credit">
                    Solicitamos su permiso para obtener datos estadísticos de su navegación en esta web, en cumplimiento del Real Decreto-ley 13/2012.
                    Si continúa navegando consideramos que acepta el uso de cookies. <a target="_blank" href="{{url('cookiesPolicy')}}">Más Información.</a>
                </p>
                <br>
                <button class="btn btn-danger centerHorizontal mb-4 acceptCookies">Aceptar</button>
            </div>
        </div>

    </nav>
</div>

<!-- E N D   C O O K I E S   P O P   U P  -->


@if($errors->any())

    <!-- E R R O R S -->

    <div id="errorModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-4 mb-4">
                        <strong>Error</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><i id="modalSignal" class="glyphicon glyphicon-alert centerHorizontal pb-4"></i></div>
                    <div class="row"><p class="text-center px-5 pb-2 w-100"><strong>{{$errors->first()}}</strong></p></div>
                </div>
            </div>
        </div>
    </div>

    <script id="errorScript" type="text/javascript">
        $('#errorModal').modal('toggle');
        $('#errorScript').remove();
    </script>

    <!-- E N D   E R R O R S -->

@endif

@if(Session::has('successMessage'))

    <!-- O T H E R   M E S S A G E S   A N D   A L T E R N A T E   B E H A V I O U R S -->

    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-3 mb-4 mt-3">
                        <span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row py-5"><i id="modalSignal" class="glyphicon glyphicon-ok centerHorizontal mb-4"></i></div>
                    <div class="row">
                        <p class="text-center px-5 w-100">
                            <strong class=" py-2 d-block">{{Session::get('successMessage')}}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script id="messageScript" type="text/javascript">
        $('#messageModal').modal('toggle');
        $('#messageScript').remove();
    </script>

    <!-- E N D   O T H E R   M E S S A G E S   A N D   A L T E R N A T E   B E H A V I O U R S -->

@endif

<!-- N A V B A R -->

<div class="container-fluid nav-container fullViewWidth">
    <nav class="navbar navbar-expand-lg navbar-light ">
        <!-- N A V - I T E M S   L E F T -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link interactive" href="{{url('adminEditPage/index')}}">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle interactive" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Equipo
                    </a>
                    <div class="dropdown-menu container" aria-labelledby="navbarDropdownMenuLink">
                        <div class="row position-absolute w-100 h-100">
                            <div class="col-6">
                                <a class="dropdown-item teamItem" href="{{url('adminEditPage/aboutUs')}}">¿Quiénes somos?</a>
                                <a class="dropdown-item teamItem" href="{{url('adminEditPage/workWithUs')}}">Trabaja con nosotros</a>
                                <a class="dropdown-item teamItem" href="{{url('editSurvey')}}">Encuesta de satisfacción</a>
                                <a class="dropdown-item teamItem" href="{{url('adminEditPage/FAQ')}}">Preguntas frecuentes</a>
                            </div>
                            <div class="col-6 h-100">
                                <img class="teamImg h-100" alt="Imagen de la sección '¿Quienes somos?'" src="{{asset('images/whoWeAre.jpg')}}">
                                <img class="teamImg displayNone h-100" alt="Imagen de la sección 'Trabaja con nosotros'" src="{{asset('images/workWithUs.jpg')}}">
                                <img class="teamImg displayNone h-100" alt="Imagen de la sección 'Encuesta de satisfacción'" src="{{asset('images/press.jpg')}}">
                                <img class="teamImg displayNone h-100" alt="Imagen de la sección 'Preguntas frecuentes'" src="{{asset('images/FAQ.jpg')}}">
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle interactive" id="navbarDropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                        Catálogo
                    </a>
                    <div class="dropdown-menu container" aria-labelledby="navbarDropdownMenuLink">
                        <div class="row position-absolute w-100 h-100">
                            <div class="col-lg-6 col-12 h-100 overflow-auto">
                                <?php
                                foreach($categories as $currentCategory){
                                    echo'<a class="dropdown-item catalogItem" data-content="'.$currentCategory['category'].'" href="'.url('adminEditPage/catalogue/'.$currentCategory['category']).'"><span class="d-inline-block text-overflow-ellipsis overflow-hidden w-75">'.$currentCategory['category'].'</span></a>';
                                }
                                ?>
                                <form class="inputWithIcon w-90 ml-3 mt-2">
                                    <input type="text" id="addCategoryTextField" class="form-control" placeholder="Añadir categoría" name="category" autocomplete="off" required/>
                                    <button title="Añadir" id="addCategory" type="button" class="glyphicon glyphicon-plus beforeBlue interactive p-2 m-1 right"></button>
                                </form>
                            </div>
                            <div class="col-6 rightHalf h-100">
                                <?php
                                foreach($categories as $currentCategory){
                                    echo'<img class="h-100" data-content="'.$currentCategory['category'].'" src="'.asset('images/uploads/'.$currentCategory['sampleBookImage']).'">';
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- N A V B A R - B R A N D -->
        <a class="navbar-brand position-absolute centerHorizontal" href="{{url('controlPanel')}}">
            <img src="{{asset('images/paraleloLogoAdmin.png')}}" width="100%" alt="Logo de la Editorial Paralelo 28">
        </a>
        <!-- N A V - I T E M S   R I G H T -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a href="{{url('adminEditPage/blog')}}" class="nav-link interactive">Blog</a>
                </li>
                <li class="nav-item ">
                    <a href="{{url('adminEditPage/contact')}}" class="nav-link interactive">Contacto</a>
                </li>
                <li class="nav-item ml-6 position-relative">
                    <a class="nav-link px-1" <?php if($userData) echo'href="'.url('controlPanel').'"' ?>>
                        <?php if($userData) echo'<small class="userName d-inline-block" >'.$userData['name'].'</small>' ?>
                        <i title="Acceso para usuarios" class="navIcons icon-people"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                </li>
            </ul>
        </div>
        <i class="glyphicon glyphicon-menu-hamburger position-absolute right absoluteCenterVertical hoverRed" data-toggle="collapse" data-target=".navbar-collapse"></i>
    </nav>
    <form class="searchForm" method="POST" action="{{url('search')}}">
        {{csrf_field()}}
        <input type="text" class="form-control" name="query" placeholder="Buscar en EditorialParalelo28" autocomplete="off" required>
        <button type="submit" class="btn btn-danger bg-red-color noBorderRadius"><strong>Buscar</strong></button>
        <span class="closeSearch white-color">&times;</span>
    </form>
</div>

<!-- A D M I N   A D D   C A T E G O R Y   M O D A L -->

<div class="modal" id="modalAdminUploadCategory" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title centerHorizontal"></h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form class="form-group" method="post" action="{{url('adminAddCategory')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" id="category" name="category" required/>
                    <div class="modal-body px-lg-5 px-3">
                    <h6>Imagen para el banner</h6>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input interactive" id="uploadBannerImg" accept="image/*" name="bannerImg" required>
                        <label class="custom-file-label" for="uploadBannerImg" data-browse="Seleccionar Archivo">
                            <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                        </label>
                    </div>

                    <h6 class="mt-4">Imagen para el menu</h6>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input interactive" id="uploadNavBarImg" accept="image/*" name="navBarImg" required>
                        <label class="custom-file-label" for="uploadNavBarImg" data-browse="Seleccionar Archivo">
                            <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                        </label>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                        <button type="submit" class="btn btn-primary bg-blue-color"><strong>Añadir categoría</strong></button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   A D D   C A T E G O R Y   M O D A L -->

<!-- A D M I N   E D I T   C A T E G O R Y   M O D A L -->

<div class="modal" id="modalAdminEditCategory" tabindex="-1" role="dialog">
    <div class="modal-dialog h-100 my-0" role="document">
        <div class="modal-content absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title centerHorizontal">Editar</h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="post" action="{{url('adminEditCategory')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="oldCategory" name="oldCategory" required/>
                <div class="modal-body px-lg-5 px-3">
                    <h6>Nombre</h6>

                    <input type="text" id="newCategory" class="form-control" name="newCategory">

                    <h6 class="mt-4">Imagen para el banner</h6>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input interactive" id="uploadBannerImg" accept="image/*" name="bannerImg">
                        <label class="custom-file-label" for="uploadBannerImg" data-browse="Seleccionar Archivo">
                            <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                        </label>
                    </div>

                    <h6 class="mt-4">Imagen para el menu</h6>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input interactive" id="uploadNavBarImg" accept="image/*" name="navBarImg">
                        <label class="custom-file-label" for="uploadNavBarImg" data-browse="Seleccionar Archivo">
                            <p class="w-50 text-overflow-ellipsis">Sube tu imagen</p>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Editar categoría</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   E D I T   C A T E G O R Y   M O D A L -->

<!-- A D M I N   D E L E T E   M O D A L -->

<div class="modal" id="modalAdminDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg h-100 my-0" role="document">
        <div class="modal-content mh-100 overflow-auto absoluteCenterBoth">
            <div class="modal-header">
                <h5 class="modal-title centerHorizontal"></h5>
                <button type="button" class="close hoverRed" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" method="post" action="{{url('adminDelete')}}">
                {{csrf_field()}}
                <input type="hidden" id="deleteField" required/>
                <div class="modal-body px-lg-5 px-3">
                    <h6 class="mb-5 text-center">¿Que hacemos con los siguientes contenidos?</h6>
                    <div class="modal-innerContent"></div>
                    <div class="row mt-5">
                        <div class="col-lg-8 offset-lg-2 col-12 offset-0 row">
                            <div class="col-lg-5 offset-lg-1 col-6 offset-0">
                                <div class="form-check text-center mt-2">
                                    <input type="checkbox" class="form-check-input mt-2" id="changeAll" name="changeAll">
                                    <label class="form-check-label noWrap pb-3" for="changeAll">Aplicar para todos:</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <select class="form-control adminRefactorSelect" name="applyForAll"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Cancelar</strong></button>
                    <button type="submit" class="btn btn-primary bg-blue-color"><strong>Borrar</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   D E L E T E   M O D A L -->

<script type="text/javascript" id="allCategoriesScript">
    let allCategories = [];

    <?php

    foreach($categories as $currentCategory){
        echo 'allCategories.push("'.$currentCategory['category'].'");';
    }

    ?>

    $(window).on("load", function () {
        $('#allCategoriesScript').remove();
    });
</script>

@show
@yield('content')
@section('footer')

<script type="text/javascript" src="{{asset('js/utilities.min.js')}}" defer></script>
<script type="text/javascript" src="{{asset('js/layout.min.js')}}" defer></script>
<script type="text/javascript" src="{{asset('js/admin.min.js')}}" defer></script>

</body>
</html>
@show
