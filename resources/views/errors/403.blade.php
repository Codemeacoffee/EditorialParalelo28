@extends('errors/errorLayout')
@section('header')
@section('content')

<!-- E R R O R   C O N T E N T -->

<div class="errorBox absoluteCenterBoth">
    <div class="row">
        <h1 class="col-4 offset-8 pr-4 text-right">Error</h1>
    </div>
    <div class="d-flex justify-content-center">
        <span class="">4</span>

        <!-- C S S   B L A C K   H O L E -->

        <div class="bg-black-color px-3">
            <b></b>
        </div>

        <!-- E N D   C S S   B L A C K   H O L E -->

        <span class=" p-0">3</span>
    </div>
    <div class="row mt-5">
        <h5 class="col-12 text-center px-4">
            No deberías estar aquí. <br/> En el espacio no hay aire.
        </h5>
    </div>
    <div class="row mt-4">
        <div class="fadeInUpToBottom col-12">
            <div class="d-flex justify-content-center flex-wrap">
                <a class="px-lg-2 px-4" href="{{url('/')}}">Inicio</a>
                <a class="px-lg-2 px-4" href="{{url('/aboutUs')}}">Equipo</a>
                <a class="px-lg-2 px-4" href="{{url('/catalogue')}}">Catalogo</a>
                <a class="px-lg-2 px-4" href="{{url('/blog')}}">Blog</a>
                <a class="px-lg-2 px-4" href="{{url('/contact')}}">Contacto</a>
            </div>
        </div>
    </div>
</div>

<!-- E N D   E R R O R   C O N T E N T -->

@stop
@section('footer')
