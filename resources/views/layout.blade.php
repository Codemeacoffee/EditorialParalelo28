@section('header')
        <!DOCTYPE html>
<html itemscope lang="es" dir="ltr" itemtype="https://schema.org/WebSite">
<head>
    <title>Editorial Paralelo28</title>
    <meta name="author" content="<?php echo env("AUTHOR", "Inversiones Borma S.L."); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8">
    <!-- SEO -->
    <meta name="keywords" content="Editorial, Paralelo, 28, Paralelo28, Formación, Educación, Empleo, Manual, Manuales">
    <meta name="description" content="Somos una editorial especializado en manuales de formación no reglada de Canarias.
        Contamos con más de 115 Unidades Formativas de Hostelería y Turismo y un diseño moderno que permite al alumno hacer las actividades sobre el manual." />
    <meta property="og:site_name" content="Editorial Paralelo28">
    <meta property="og:title" content="Editorial Paralelo28" />
    <meta property="og:url" content="https://editorialparalelo28.com/" />
    <meta property="og:description" content="Somos una editorial especializado en manuales de formación no reglada de Canarias.">
    <meta property="og:image" itemprop="image" content="https://editorialparalelo28.com/images/Paralelo28Seo.jpg/">
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="es_ES" />
    <meta property="og:updated_time" content="{{strtotime(date('Y-m-d'))}}"/>
    <!-- SEO -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/swiper.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/glyphicon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/home.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrapAdaptations.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/navBar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/modals.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/lateralMenu.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/shoppingCart.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/catalogue.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/blog.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bookViewer.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/footer.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/utils.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/effects.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/responsive.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/paralelo28/style.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('js/jquery-ui-1.12.1/jquery-ui.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/quilljsSnow.min.css')}}">
    <link rel="shortcut icon" type="image/png" href= "{{asset('images/paralelo28favicon.png')}}"/>
    <link rel="preload" href="{{asset('images/loading.svg')}}" as="image">
    <script type="text/javascript" src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-ui-1.12.1/jquery-ui.min.js')}}" defer></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/quilljs.min.js')}}" defer></script>
    <script type="text/javascript" src="{{asset('js/swiper.min.js')}}" defer></script>
    <script type="text/javascript" src="{{asset('js/pselect.min.js')}}" defer></script>
    <script type="text/javascript" src="{{asset('js/cookies.min.js')}}" defer></script>
    <script class="swiperNames" type="text/javascript">let swiperNames = [];</script>
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

    if($userData){
        echo'<script id="sessionScript" type="text/javascript">let session = true; let physicalTax = '.$userData["taxPhysical"].'; let digitalTax = '.$userData["taxDigital"].';</script>';
    }else{
        echo'<script id="sessionScript" type="text/javascript">let physicalTax = '.$defaultPhysicalTax.'; let digitalTax = '.$defaultDigitalTax.'</script>';
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
        <img width="25%" height="25%" class="absoluteCenterBoth" alt="Icono de carga" src="{{asset('images/loading.svg')}}">
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
                <button class="btn btn-danger centerHorizontal mb-4 acceptCookies"><strong class="px-4">Aceptar</strong></button>
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

@if(Session::has('loginOpen'))

    <!-- S E S S I O N   M E S S A G E S  -->

    <script id="messageScript" type="text/javascript">
        $(document).ready(function () {
            showUserAccess();
            $('#messageScript').remove();
        });
    </script>

    <!-- E N D   S E S S I O N   M E S S A G E S  -->

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
                            <strong class="py-2 d-block">{{Session::get('successMessage')}}</strong>
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

@if(Session::has('confirmationEmail'))

    <!-- C O N F I R M A T I O N   E M A I L   M O D A L S -->

    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-3 mb-4 mt-3">
                        <strong>¡Enhorabuena!</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><i id="modalSignal" class="glyphicon glyphicon-envelope centerHorizontal mb-4"></i></div>
                    <div class="row">
                        <p class="text-center px-5 w-100">
                            Te hemos enviado un email a:
                            <strong class=" py-2 d-block">{{Session::get('confirmationEmail')}}</strong>
                            Si no recibes el email en unos minutos, revisa la carpeta de spam.
                            <br>
                            En caso de que no te haya llegado, haz click en este enlace:
                            <a class="py-2 d-block" href="{{url('resendConfirmationEmail/'.Session::get('confirmationEmail'))}}">Volver a enviarme la confirmación</a>
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
@endif

@if(Session::has('emailChanged'))
    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-3 mb-4 mt-3">
                        <strong>Ha cambiado con éxito su correo</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><i id="modalSignal" class="glyphicon glyphicon-envelope centerHorizontal mb-4"></i></div>
                    <div class="row">
                        <p class="text-center px-5 w-100">
                            Para que confirmes tu nuevo correo te hemos enviado un email a:
                            <strong class=" py-2 d-block">{{Session::get('emailChanged')}}</strong>
                            Si no recibes el email en unos minutos, revisa la carpeta de spam.
                            <br>
                            En caso de que no te haya llegado envía un reporte a nuestro servicio técnico:
                            <a class="py-2 d-block" href="{{url('contact')}}#clientSupport">Servicio técnico</a>
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
@endif

@if(Session::has('resendEmail'))
    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-3 mb-4 mt-3">
                        <strong>Confirmación Reenviada</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><i id="modalSignal" class="glyphicon glyphicon-envelope centerHorizontal mb-4"></i></div>
                    <div class="row">
                        <p class="text-center px-5 w-100">
                            Te hemos reenviado el email de confirmación a:
                            <strong class=" py-2 d-block">{{Session::get('resendEmail')}}</strong>
                            Si sigues teniendo problemas con la confirmación de tu email,<br>
                            envía un reporte a nuestro servicio técnico:
                            <a class="py-2 d-block" href="{{url('technicalServiceReport')}}">Servicio técnico</a>
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
@endif

@if(Session::has('notConfirmed'))
    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-3 mb-4 mt-3">
                        <strong>Cuenta sin confirmar</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><i id="modalSignal" class="glyphicon glyphicon-envelope centerHorizontal mb-4"></i></div>
                    <div class="row">
                        <p class="text-center px-5 w-100">
                            Cuando creó su cuenta le enviamos un email de confirmación a:
                            <strong class=" py-2 d-block">{{Session::get('notConfirmed')}}</strong>
                            Si no recibiste el email, revisa la carpeta de spam.
                            <br>
                            En caso de que no te haya llegado, haz click en este enlace:
                            <a class="py-2 d-block" href="{{url('resendConfirmationEmail/'.Session::get('notConfirmed'))}}">Volver a enviarme la confirmación</a>
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
@endif

@if(Session::has('retryEmailTime'))
    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-3 mb-4 mt-3">
                        <strong>Reintentar confirmación</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><i id="modalSignal" class="glyphicon glyphicon-envelope centerHorizontal mb-4"></i></div>
                    <div class="row">
                        <p class="text-center px-5 w-100">
                            Ha excedido el limite de reenvio de emails para tu cuenta.<br>
                            Puedes volver a intentarlo en:
                            <strong id="retryEmailConfirmationTimer" class="py-2 d-block" data-content="{{Session::get('retryEmailTime')}}" data-memory="{{url('resendConfirmationEmail/'.Session::get('retryEmail'))}}"></strong>
                            Si sigues teniendo problemas puedes enviar un <br>
                            reporte a nuestro servicio técnico:
                            <a class="py-2 d-block" href="{{url('contact')}}#clientSupport">Servicio técnico</a>
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

    <!-- E N D   C O N F I R M A T I O N   E M A I L   M O D A L S -->

@endif

<!-- N A V B A R -->

<div class="container-fluid nav-container">
    <nav class="navbar navbar-expand-lg navbar-light ">
        <!-- N A V - I T E M S   L E F T -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link interactive" href="{{url('/')}}">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle interactive" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Equipo
                    </a>
                    <div class="dropdown-menu container" aria-labelledby="navbarDropdownMenuLink">
                        <div class="row position-absolute w-100 h-100">
                            <div class="col-lg-6 col-12">
                                <a class="dropdown-item teamItem" href="{{url('aboutUs')}}">¿Quiénes somos?</a>
                                <a class="dropdown-item teamItem" href="{{url('workWithUs')}}">Trabaja con nosotros</a>
                                <a class="dropdown-item teamItem" href="{{url('survey')}}">Encuesta de satisfacción</a>
                                <a class="dropdown-item teamItem" href="{{url('FAQ')}}">Preguntas frecuentes</a>
                            </div>
                            <div class="col-lg-6 col-12 h-100 p-0">
                                <img class="teamImg w-100 h-100" alt="Imagen de la sección '¿Quienes somos?'" src="{{asset('images/whoWeAre.jpg')}}">
                                <img class="teamImg displayNone w-100 h-100" alt="Imagen de la sección 'Trabaja con nosotros'" data-src="{{asset('images/workWithUs.jpg')}}">
                                <img class="teamImg displayNone w-100 h-100" alt="Imagen de la sección 'Encuesta de satisfacción'" data-src="{{asset('images/press.jpg')}}">
                                <img class="teamImg displayNone w-100 h-100" alt="Imagen de la sección 'Preguntas frecuentes'" data-src="{{asset('images/FAQ.jpg')}}">
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle interactive" id="navbarDropdownMenuLink" aria-haspopup="true" aria-expanded="false" href="{{url('catalogue')}}">
                        Catálogo
                    </a>
                    <div class="dropdown-menu container" aria-labelledby="navbarDropdownMenuLink">
                        <div class="row position-absolute w-100 h-100">
                            <div class="col-lg-6 col-12 h-100 overflow-auto">
                                <?php
                                foreach($categories as $currentCategory){
                                    echo'<a class="dropdown-item catalogItem text-overflow-ellipsis overflow-hidden" data-content="'.$currentCategory['category'].'" href="'.url('/catalogue/'.$currentCategory['category']).'">'.$currentCategory['category'].'</a>';
                                }
                                ?>
                            </div>
                            <div class="col-lg-6 col-12 rightHalf h-100">
                                <?php
                                $first = true;
                                foreach($categories as $currentCategory){
                                    if($first){
                                        echo'<img class="h-100" alt="Imagen de '.$currentCategory['category'].'" data-content="'.$currentCategory['category'].'" src="'.asset('images/uploads/'.$currentCategory['sampleBookImage']).'">';
                                        $first = false;
                                    }else echo'<img class="h-100" alt="Imagen de '.$currentCategory['category'].'" data-content="'.$currentCategory['category'].'" data-src="'.asset('images/uploads/'.$currentCategory['sampleBookImage']).'">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- N A V B A R - B R A N D -->
        <a class="navbar-brand position-absolute centerHorizontal" href="{{url('/')}}">
            <img src="{{asset('images/paraleloLogo.png')}}" width="100%" alt="Logo de la Editorial Paralelo28">
        </a>

        <i class="glyphicon glyphicon-menu-hamburger position-absolute right absoluteCenterVertical hoverRed" data-toggle="collapse" data-target=".navbar-collapse"></i>
        <!-- N A V - I T E M S   R I G H T -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a href="{{url('blog')}}" class="nav-link interactive">Blog</a>
                </li>
                <li class="nav-item ">
                    <a href="{{url('contact')}}" class="nav-link interactive">Contacto</a>
                </li>
                <div class="d-flex mediaControls">
                    <li class="nav-item ml-6 position-relative">
                        <a class="nav-link px-1" <?php if($userData) echo'href="'.url('home').'"' ?>>
                            <?php if($userData) echo'<small class="userName d-inline-block" >'.$userData['name'].'</small>' ?>
                            <i title="Acceso para usuarios" class="navIcons icon-people <?php if($userData) echo 'mediaSession'  ?>"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-1"><i title="Carrito de la compra" class="navIcons icon-cart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-1"><i title="Buscar en la web" class="navIcons icon-search"></i></a>
                    </li>

                </div>
            </ul>
        </div>
    </nav>
    <form class="searchForm" method="GET" action="{{url('search')}}">
        <input type="text" class="form-control" name="question" placeholder="Buscar en EditorialParalelo28" autocomplete="off" required>
        <button type="submit" class="btn btn-danger bg-red-color noBorderRadius"><strong>Buscar</strong></button>
        <span class="closeSearch white-color">&times;</span>
    </form>
</div>


<div id="userAccess" class="rightLateralMenu" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal" role="document">
        <div class="modal-content overflow-auto">
            <div class="modal-body">
                <div id="accessPanel" class="container-fluid">
                    <h2 class="text-center ml-4 mb-4">
                        <strong>Acceder</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess" aria-hidden="true">×</span>
                    </h2>
                    <p class="text-center mb-4"><strong><u id="createAccount" class="red-color hoverRed interactive">Crear cuenta</u></strong></p>
                    <form method="POST" action="{{url('login')}}">
                        {{csrf_field()}}
                        <div class="form-group mb-3">
                            <label for="accessEmail">Email</label>
                            <input id="accessEmail" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Introduce tu email" name="email">
                            <small class="text-danger formError">&nbsp;</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="accessPass">Contraseña</label>
                            <input id="accessPass" type="password" class="form-control" placeholder="Introduce tu contraseña" name="password" autocomplete="off">
                            <small class="text-danger formError">&nbsp;</small>
                        </div>
                        <p class="text-center mb-4"><u><a id="passwordReset" class="red-color hoverRed" href="{{url('passwordReset')}}">¿Olvidaste tu contraseña?</a></u></p>
                        <button type="submit" class="btn btn-danger shoppingCartButton"><strong>Entrar</strong></button>
                    </form>
                </div>
                <div id="createAccountPanel" class="container-fluid displayNone">
                    <h2 class="text-center ml-4 mb-4">
                        <strong>Crear cuenta</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess" aria-hidden="true">×</span>
                    </h2>
                    <p class="text-center mb-4"><strong><u id="accessAccount" class="red-color hoverRed interactive">Acceder</u></strong></p>
                    <form method="POST" action="{{url('createAccount')}}">
                        {{csrf_field()}}
                        <div class="form-group mb-3">
                            <label for="createAccountEmail">Email</label>
                            <input id="createAccountEmail" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Introduce tu email" name="email">
                            <small class="text-danger formError">&nbsp;</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="createAccountPass">Contraseña</label>
                            <input id="createAccountPass" type="password" class="form-control" placeholder="Introduce tu contraseña" name="password" autocomplete="off">
                            <small class="text-danger formError">&nbsp;</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="createAccountRepeatPass">Confirmar contraseña</label>
                            <input id="createAccountRepeatPass" type="password" class="form-control" placeholder="Repite tu contraseña" name="passwordRepeat" autocomplete="off">
                            <small class="text-danger formError">&nbsp;</small>
                        </div>
                        <p class="mb-4"><small>Al registrarte aceptas nuestros <u><a class="red-color hoverRed" href="{{url('termsAndConditions')}}">terminos y condiciones</a></u> y <u><a class="red-color hoverRed" href="{{url('privacyPolicy')}}">políticas de privacidad</a></u></small></p>
                        <button type="submit" class="btn btn-danger shoppingCartButton"><strong>Regístrate</strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="shoppingCart" class="rightLateralMenu" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal" role="document">
        <div class="modal-content">
            <div class="modal-body overflow-auto">
                <div class="container-fluid">
                    <h2 class="text-center ml-4">
                        <strong>Resumen del carrito</strong>
                        <span id="closeCart" class="theX interactive float-right hoverRed" aria-hidden="true">×</span>
                    </h2>
                    <div class="shoppingCartRedline"></div>
                    <div class="emptyCart overflow-hidden">
                        <h5 class="text-center"><strong>Su carro esta vacío</strong></h5>
                        <h6 class="text-center">Cuando añada un libro, aparecerá aquí</h6>
                        <img class="w-75 centerHorizontal" alt="Imagen de un carrito vacío." src="{{asset('images/cartEmpty.png')}}">
                    </div>
                    <div id="shoppingCartItems"></div>
                    <div class="shoppingCartRedline"></div>
                    <div id="priceSummation">
                        <p><strong>Impuestos(<?php if($userData){
                                    echo $userData['taxName'];
                                }else{
                                    echo 'IGIC';
                                } ?>)</strong><span>0€</span></p>
                        <p><strong>Total</strong><span>0€</span></p>
                    </div>
                    <a href="{{url('shoppingCart')}}"><button type="button" class="btn btn-danger shoppingCartButton"><strong class="px-4 noWrap">Ver carrito</strong></button></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E N D   N A V B A R -->

<!-- Z O O M   I M A G E   B O X -->

<div id="zoomImageBox">
    <img alt="" src="">
</div>

<!-- E N D   Z O O M   I M A G E   B O X -->


@show
@yield('content')
@section('footer')

<!-- T I T L E -->
<div class="container-fluid">
    <hr class="index-red-line" size="30">
</div>
<div class="container-fluid genericFields">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div><div class="ed-text" data-content="title-4"><?php echo $genericFields['title-4'] ?></div></div>
            <br>
            <div><div class="ed-text" data-content="text-4"><?php echo $genericFields['text-4'] ?></div></div>
        </div>
        <div class="col-12 col-lg-4">
            <div><div class="ed-text" data-content="title-5"><?php echo $genericFields['title-5'] ?></div></div>
            <br>
            <div><div class="ed-text" data-content="text-5"><?php echo $genericFields['text-5'] ?></div></div>
        </div>
        <div class="col-12 col-lg-4">
            <div><div class="ed-text" data-content="title-6"><?php echo $genericFields['title-6'] ?></div></div>
            <br>
            <div><div class="ed-text" data-content="text-6"><?php echo $genericFields['text-6'] ?></div></div>
        </div>
    </div>
</div>
<!-- E N D   T I T L E -->

<!-- N E W S L E T T E R -->
    <div class="newsletter-bg-color">
        <div class="container-fluid position-relative">
            <div class="row py-5">
                <div class="col-md-12 col-lg-6">
                    <form method="POST" action="{{url('newsletterSubscription')}}">
                        {{csrf_field()}}
                        <h2><label for="newsletterMail" class="interactive"><strong>Suscríbete al Newsletter</strong></label></h2>
                        <p>Mantente informado de todas nuestras novedades, promociones, noticias, etc</p>
                        <input id="newsletterMail" class="newsletter-inputfield px-2" type="email" name="mail" placeholder="Introduce tu Email" required>
                        <button type="submit" class="btn btn-danger ml-3"><strong>Suscribirme</strong></button>
                        <br>
                        <div class="mt-3">
                            <input id="newsletterPoliticsConfirmation" type="checkbox" name="subscribe" required>
                            <label for="newsletterPoliticsConfirmation" class="ml-2"> He leído y acepto la <a href="{{url('privacyPolicy')}}">política de privacidad</a></label>
                        </div>
                    </form>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="newsletter-left">
                        <strong>Síguenos en nuestras redes sociales</strong>
                        <a class="socialMedia px-2" href="https://es-es.facebook.com/editorialparalelo28" target="_blank"><i title="Facebook de Editorial Paralelo28" class="icon-facebook"></i></a>
                        <a class="socialMedia px-2" href="https://www.instagram.com/editorialparalelo28" target="_blank"><i title="Instagram de Editorial Paralelo28" class="icon-instagram"></i></a>
                        <a class="socialMedia px-2" href="https://wa.me/34699511741" target="_blank"><i title="Whatsapp de Editorial Paralelo28" class="icon-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- E N D   N E W S L E T T E R -->

<!-- F O O T E R -->

    <footer class="footer-bg-color">
        <div class="container-fluid text-white">
            <div class="row py-lg-5 py-md-5 py-sm-5 py-3">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <h5 class="font-weight-bold mt-3">Editorial Paralelo28</h5>
                        <a href="https://www.google.com/maps/place/Calle+de+P%C3%A9rez+del+Toro,+54,+35004+Las+Palmas+de+Gran+Canaria,+Las+Palmas/@28.1164064,-15.4288728,17z/data=!3m1!4b1!4m5!3m4!1s0xc40959e28cbc48f:0x94ecafb8a44147ee!8m2!3d28.1164017!4d-15.4266841" target="_blank">
                            <ul class="list-unstyled mb-0">
                                <li>
                                    C/ Pérez del Toro, 54-56. 35004.
                                </li>
                                <li>
                                    Las Palmas de GC - GC -España
                                </li>
                            </ul>
                        </a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="https://wa.me/34699511741" target="_blank">Tel. 699 511 741</a>
                        </li>
                        <li>
                            <a href="mailto:info@editorialparalelo28.com" target="_blank">info@editorialparalelo28.com</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <h5 class="font-weight-bold mt-3">Ayuda a la compra</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/contact')}}" href="{{url('contact')}}#clientSupport">Atención al Cliente</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/shipmentsAndRefundsPolicy')}}" href="{{url('shipmentsAndRefundsPolicy')}}">Política de envíos y devoluciones</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('editSurvey')}}" href="{{url('survey')}}">Encuesta de satisfacción</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/paymentMethods')}}" href="{{url('paymentMethods')}}">Métodos de pago</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <h5 class="font-weight-bold mt-3">Acerca de</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/aboutUs')}}" href="{{url('aboutUs')}}">Nosotros</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/contact')}}" href="{{url('contact')}}">Contacto</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/blog')}}" href="{{url('blog')}}">Blog</a>
                        </li>
                        <li>
                            <a href="{{url('catalogue')}}">Productos</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <h5 class="font-weight-bold mt-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/legalAdvice')}}" href="{{url('legalAdvice')}}">Aviso legal</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/termsAndConditions')}}" href="{{url('termsAndConditions')}}">Terminos y condiciones</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/privacyPolicy')}}" href="{{url('privacyPolicy')}}">Política de privacidad</a>
                        </li>
                        <li>
                            <a class="ed-link" data-target="{{url('adminEditPage/cookiesPolicy')}}" href="{{url('cookiesPolicy')}}">Política de cookies</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="footer-copyright text-white py-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-center align-items-center">
                        <strong class="noWrap">© 2019 Editorial Paralelo28</strong>
                    </div>
                    <div class="col-xl-5 offset-xl-5 col-lg-7 offset-lg-3 col-md-8 offset-md-1 d-md-inline d-none">
                        <div class="container">
                            <a href="{{url('paymentMethods')}}">
                                <div class="row">
                                    <div class="col-2">
                                        <img class="w-100" alt="Icono simbolizando la aceptación de Visa" src="{{asset('images/cards-1.png')}}">
                                    </div>
                                    <div class="col-2">
                                        <img class="w-100" alt="Icono simbolizando la aceptación de Mastercard" src="{{asset('images/cards-2.png')}}">
                                    </div>
                                    <div class="col-2">
                                        <img class="w-100" alt="Icono simbolizando la aceptación de American Express" src="{{asset('images/cards-3.png')}}">
                                    </div>
                                    <div class="col-2">
                                        <img class="w-100" alt="Icono simbolizando la aceptación de JCB" src="{{asset('images/cards-4.png')}}">
                                    </div>
                                    <div class="col-2">
                                        <img class="w-100" alt="Icono simbolizando la aceptación de Diners Club International" src="{{asset('images/cards-5.png')}}">
                                    </div>
                                    <div class="col-2">
                                        <img class="w-100" alt="Icono simbolizando la aceptación de Citicorp" src="{{asset('images/cards-6.png')}}">
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- E N D   F O O T E R -->
<script type="text/javascript" src="{{asset('js/utilities.min.js')}}" defer></script>
<script type="text/javascript" src="{{asset('js/layout.min.js')}}" defer></script>
<script type="text/javascript" src="{{asset('js/main.min.js')}}" defer></script>
<script type="text/javascript" src="{{asset('js/shoppingCart.min.js')}}" defer></script>

</body>
</html>
@show
