@extends('layout')
@section('header')
@section('content')

<!-- B L O G   E N T R Y -->

<div class="container-fluid mt-5 blogEntryBody">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="w-75 centerHorizontal">
                <div class="pb-3"><small><?php echo $data[0]['category'] ?></small></div>
                <h1 class="mb-5"><strong><?php echo $data[0]['title'] ?></strong></h1>
                <div>
                    <?php echo $data[0]['content'] ?>
                </div>
                <div class="row py-3">
                    <div class="col-6 interactive"><strong><a class="blogEntryAnchor" href="{{url('blog/'.$data[0]['title'].'?action=before')}}"> < Anterior </a></strong></div>
                    <div class="col-6 text-right interactive"><strong><a class="blogEntryAnchor" href="{{url('blog/'.$data[0]['title'].'?action=next')}}"> Siguiente > </a></strong></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <img class="w-75 pt-4" alt="Imagen de la entrada {{$data[0]['title']}}" src="{{asset('images/uploads/'.$data[0]['imgLink'])}}">
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-xl-10 offset-xl-1 col-12 offset-0">
            <h2 class="mb-5 text-center"><strong>Relacionados</strong></h2>
            <div class="row col-12 mt-4 px-0 blogRow m-0">
            <?php

            foreach($data[1] as $otherNews){
                echo '<div class="col-lg-4 col-12 h-200 mb-4 overflow-hidden entryData pr-0">
                <div class="row bg-slateGrey-color rounded h-100 position-relative entry mr-4">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 py-3 h-100">
                <small id="entryCategory">'.$otherNews['category'].'</small>
                <h6 id="entryTitle" data-id="'.$otherNews['id'].'" class="mb-4 mt-2">'.$otherNews['title'].'</h6>
                <a data-target="'.$otherNews['title'].'" class="btn btn-danger white-color mt-4 ed-link anim-none" href="'.url('blog/'.$otherNews['title']).'"><div class="px-4"><strong>Leer</strong></div></a>
                <div id="entryContent" class="d-none">'.$otherNews["content"].'</div>
                </div>
                 <div class="col-lg-6 col-md-6 col-sm-6 col-12 pl-2 pr-0 h-100 overflow-hidden">
                <div class="w-100 h-100 position-absolute blogBgImage" style="background-image: url('.asset("images/uploads/".$otherNews["imgLink"]).')"></div>
                <img id="entryImage" class="absoluteCenterBoth h-75" alt="Imagen de la noticia '.$otherNews['title'].'" src="'.asset("images/uploads/".$otherNews["imgLink"]).'">
                </div>
                </div>
                </div>';
            }
            ?>
            </div>
        </div>
    </div>
</div>

<!-- E N D   B L O G   E N T R Y  -->

@stop
@section('footer')
