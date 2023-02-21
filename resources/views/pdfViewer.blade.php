@extends('layout')
@section('header')
@section('content')

<!-- P D F   V I E W E R -->

<div id="bookViewer">
    <div class="row bookViewerHead">
        <div class="col-4 text-overflow-ellipsis">
            <a href="{{url('home')}}" class="interactive white-color hoverRed pr-4"><i title="Atrás" class="glyphicon glyphicon-chevron-left"></i></a>
            <strong>
                <?php echo $data[0] ?>
            </strong>
        </div>
        <div id="pageIndex" class="col-4 text-center"></div>
        <div class="col-4">
            <div class="row float-right">
                <a class="white-color d-lg-inline d-none" target="_blank" href="{{url('printBook/'.$data[0])}}"><div class="pr-5 interactive hoverRed"><i title="Imprimir" class="glyphicon glyphicon-print"></i></div></a>
                <a class="white-color" target="_blank" href="{{url('downloadBook/'.$data[0])}}"><div class="pr-5 interactive hoverRed"><i title="Descargar" class="glyphicon glyphicon-download-alt"></i></div></a>
            </div>
        </div>
    </div>
    <div class="pages">
        <?php

        $first = true;

        foreach ($data[1] as $image){
            if($first){
                echo '<img class="page centerHorizontal" src="'.asset('files/bookPreviews/'.$image['imgSrc']).'">';
                $first = false;
            }else{
                echo '<img class="page centerHorizontal" data-content="'.asset('files/bookPreviews/'.$image['imgSrc']).'">';
            }
        }
        ?>
    </div>
</div>

<!-- E N D   P D F   V I E W E R -->

<script type="text/javascript" src="{{asset('js/utilities.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/layout.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bookViewer.min.js')}}"></script>
@stop
@section('footer')
@stop
