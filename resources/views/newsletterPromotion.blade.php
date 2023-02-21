@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N   N E W S L E T T E R -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Newsletter</strong></h1>

    <div class="row mt-6 position-relative">
        <div class="col-lg-6 offset-lg-3 col-12 offset-0">
            <form method="post" action="{{url('emailAllSubscribers')}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label for="subject">Asunto*</label>
                    <input type="text" class="form-control" id="subject" aria-describedby="emailSubject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="imageLink">Imagen (Enlace)</label>
                    <input type="text" class="form-control" id="imageLink" aria-describedby="imageLink" name="imageLink">
                </div>
                <p class="ml-1">Contenido*</p>
                <div id="blogEditor" class="interactive bg-white-color"></div>
                <input id="emailContent" type="hidden" name="emailContent">
                <button type="submit" class="btn btn-danger mt-3 float-right">Enviar</button>
            </form>
            <div class="pr-5 mt-4 form-group">
                <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('controlPanel')}}">Volver</a></u></strong>
            </div>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   N E W S L E T T E R -->

<script type="text/javascript" src="{{'js/newsletter.min.js'}}"></script>

@stop
@section('footer')
