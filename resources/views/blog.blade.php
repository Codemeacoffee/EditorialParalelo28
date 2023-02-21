@extends('layout')
@section('header')
@section('content')

<!-- B L O G -->

<div class="container-fluid mt-5 mediaBlog">
    <h1 class="text-center mb-5"><strong>Blog</strong></h1>

    <div class="row col-lg-10 offset-lg-1 px-0 mb-5 blogFilter">
        <div class=" col-lg-4 offset-lg-8 col-md-6 offset-md-6 col-sm-8 offset-sm-4 col-12 px-0">
            <div class="d-flex">
                <form class="w-100 d-flex" action="{{url('blog')}}">
                    <label class="noWrap mr-3 mt-1" for="blogFilter">
                        Categorías
                    </label>
                    <select id="blogFilter" type="text" class="form-control" name="filter">
                        <option value="ALL">Todas</option>
                        <?php

                        foreach ($data[1] as $currentCategory){
                            echo '<option value="'.$currentCategory.'" ';
                                if(isset($_GET['filter']) && htmlspecialchars($_GET['filter']) == $currentCategory) echo 'selected';
                            echo'>'.$currentCategory.'</option>';
                        }

                        ?>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <?php

    $count = Count($data[0]);

    if($count > 0){
        echo
        '<div class="row col-xl-10 offset-xl-1 col-12 bg-slateGrey-color overflow-hidden rounded px-0 h-400 entry entryData">
        <div class="col-lg-6 col-md-6 col-sm-6 col-12 pt-4 px-5 h-100">
        <small id="entryCategory">'.$data[0][0]["category"].'</small>
        <h2 id="entryTitle" data-id="'.$data[0][0]['id'].'" class="mb-4 mt-2">'.$data[0][0]["title"].'</h2>
        <div id="entryContent" class="justify">'.$data[0][0]["content"].'</div>
        <a data-target="'.url('/').'/blog/'.$data[0][0]['title'].'" class="btn btn-danger white-color centerHorizontal mt-4 ed-link anim-none" href="'.url('blog/'.$data[0][0]['title']).'"><div class="px-4"><strong>Leer</strong></div></a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-12 pl-2 pr-0 h-100 overflow-hidden">
        <div class="w-100 h-100 position-absolute blogBgImage" style="background-image: url('.asset("images/uploads/".$data[0][0]["imgLink"]).')"></div>
        <img id="entryImage" class="absoluteCenterBoth h-75" alt="Imagen de la noticia '.$data[0][0]['title'].'" src="'.asset("images/uploads/".$data[0][0]["imgLink"]).'">
        </div>
        </div>';
    }

    if($count > 1){
        $rows = ceil($count/3);
        $j = 1;

        for($i=0; $i < $rows; $i++){
            echo'<div class="row col-xl-10 offset-xl-1 col-12 mt-4 px-0 blogRow">';

            $k = 0;

            while($j < $count && $k < 3){
                echo '<div class="col-lg-4 col-12 h-200 entryData">
                <div class="row bg-slateGrey-color overflow-hidden rounded h-100 position-relative entry '; if($k != 2) echo 'mr-2'; echo'">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 py-3 h-100">
                <small id="entryCategory">'.$data[0][$j]['category'].'</small>
                <h6 id="entryTitle" data-id="'.$data[0][$j]['id'].'" class="mb-4 mt-2">'.$data[0][$j]['title'].'</h6>
                <a data-target="'.url('/').'/blog/'.$data[0][$j]['title'].'" class="btn btn-danger white-color mt-4 ed-link anim-none" href="'.url('blog/'.$data[0][$j]['title']).'"><div class="px-4"><strong>Leer</strong></div></a>
                <div id="entryContent" class="d-none">'.$data[0][$j]["content"].'</div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 pl-2 pr-0 h-100 overflow-hidden">
                <div class="w-100 h-100 position-absolute blogBgImage" style="background-image: url('.asset("images/uploads/".$data[0][$j]["imgLink"]).')"></div>
                <img id="entryImage" class="absoluteCenterBoth h-75" alt="Imagen de la noticia '.$data[0][$j]['title'].'" src="'.asset("images/uploads/".$data[0][$j]["imgLink"]).'">
                </div>
                </div>
                </div>';
                $j++;
                $k++;
            }
            echo '</div>';
        }
    }

    ?>

    <div class="row mt-5">
        <button class="btn btn-danger white-color centerHorizontal mt-3 loadMore"><div class="px-4"><strong>Cargar más</strong></div></button>
    </div>
</div>

<!-- E N D   B L O G -->

<script type="text/javascript" src="{{'js/blog.min.js'}}"></script>

@stop
@section('footer')
