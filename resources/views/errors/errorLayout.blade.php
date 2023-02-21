@section('header')
    <!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <title>Paralelo28</title>
    <meta name="author" content="Inversiones Borma S.L.">
    <meta name="keywords" content="Editorial, Paralelo, 28,Paralelo28, Formación, Educación, Empleo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/swiper.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/glyphicon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/utils.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/effects.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/error.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrapAdaptations.min.css')}}">
    <link rel="shortcut icon" type="image/png" href= "{{asset('images/paralelo28favicon.png')}}"/>
    <script type="text/javascript"  src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
</head>
<body>

<!-- L O A D I N G   L A Y E R -->

<div class="loadingLayer">
    <div class="position-relative h-100">
        <svg width="50%" height="50%" xmlns="http://www.w3.org/2000/svg" viewBox="-200 -200 500 500" preserveAspectRatio="xMidYMid" class="lds-rolling absoluteCenterBoth">
            <circle cx="50" cy="50" fill="none" stroke="#ff0000" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138" transform="rotate(188.805 50 50)">
                <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="2s" begin="0s" repeatCount="indefinite"></animateTransform>
            </circle>
        </svg>
    </div>
</div>
<script id="loadingScript" type="text/javascript">
    $(window).on("load", function () {
        $('.loadingLayer').fadeOut("slow");
        $('#loadingScript').remove();
    });
</script>

<!-- E N D   L O A D I N G   L A Y E R  -->

<!-- C S S   S T A R   S K Y -->

<div class="stars"></div>
<div class="twinkling"></div>

<!-- E N D   C S S   S T A R   S K Y -->

@show
@yield('content')
@section('footer')

</body>
</html>
@show
