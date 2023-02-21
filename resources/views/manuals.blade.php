@extends('layout')
@section('header')
@section('content')

<!-- C A T A L O G U E -->

<div class="container-fluid mt-5 mediaBookContainer mediaManuals">
    <h1 class="text-center certificateTitle"><?php if($data[0] != 'ALL') echo $data[0]; ?></h1>

    <div class="catalogueFilter">
        <div class="innerCatalogueFilter">
            <div class="col-lg-10 col-11 d-flex">
                <label class="noWrap mr-3 mt-1" for="manualsFilterSelect">
                    Ordenar por:
                </label>
                <select id="manualsFilterSelect" data-target="{{url()->current()}}" class="browser-default custom-select interactive" name="filter">
                    <option value="1" <?php if(!isset($_GET['filter']) || $_GET['filter'] == 1) echo 'selected'; ?>>Fecha de edición</option>
                    <option value="2" <?php if(isset($_GET['filter']) && $_GET['filter'] == 2) echo 'selected'; ?>>Precio ascendente</option>
                    <option value="3" <?php if(isset($_GET['filter']) && $_GET['filter'] == 3) echo 'selected'; ?>>Precio descendente</option>
                    <option value="4" <?php if(isset($_GET['filter']) && $_GET['filter'] == 4) echo 'selected'; ?>>Orden alfabético</option>
                </select>
            </div>
            <div class="col-lg-2 col-1 d-flex">
                <span id="displayAsList" class="glyphicon glyphicon-th-list interactive hoverRed mt-2 mr-2"></span>
                <span id="displayAsGrid" class="glyphicon glyphicon-th hoverRed interactive red-color mt-2"></span>
            </div>
        </div>
    </div>
    <div class="displayAsGrid grid-3-rows">
<?php

for($i = 0; $i < Count($data[1]); $i++){
    echo '<div class="col-12 mb-6">
        <div class="row index-newBookBox bookBox" data-filter="'.$data[1][$i]["edition"].', '.$data[1][$i]["digitalPrice"].', '.$data[1][$i]["title"].'">
        <div class="col-6 index-newBookText">
        ' .$data[1][$i]["category"].'
        <br>
        <h4><strong> '.$data[1][$i]["title"]. '</strong></h4>
        <br>
        <button type="button"  class="btn btn-danger index-newBookButton seeMore" data-toggle="modal" data-target="#Modal' .$data[1][$i]['id'].'-Modal"><div class="px-4"><strong>Ver más</strong></div></button>
        </div>
        <div class="col-6">
        <img class="index-newBookImg" alt="Portada del libro ' .$data[1][$i]['title'].'" src="'.asset("images/uploads/".$data[1][$i]['previewImage']).'">';
        if($data[1][$i]['discount']) echo '<div class="bs-discount tag right"><strong>-'.$data[1][$i]['discount'].'%</strong></div>';
        echo'
        </div>
        </div>
        </div>';
}

?>

</div><div class="displayAsList">

<?php

foreach($data[1] as $currentBook){
    echo '<div class="row">
            <div class="col-12 mb-6">
            <div class="row index-newBookBox">
            <div class="col-xl-2 col-3">
            <img class="index-newBookImg" alt="Portada del libro ' .$currentBook['title'].'" src="'.asset("images/uploads/".$currentBook['previewImage']).'">
            </div>
            <div class="col-xl-8 col-6 index-newBookText">
            ' .$currentBook["category"].'
            <br>
            <h4 class="bookTitle"><strong> '.$currentBook["title"]. '</strong></h4>
            <p class="text-secondary">'.$currentBook["author"].'</p>
            </div>
            <div class="col-xl-2 col-3">';
            if($currentBook['discount']) echo '<div class="bs-discount tag right"><strong>-'.$currentBook['discount'].'%</strong></div>';
            echo'
            <div class="centerInBookBox mt-6">
            <h1><strong> '.$currentBook["digitalPrice"].'€</strong></h1>
            <button type="button"  class="btn btn-danger seeMore now" data-toggle="modal" data-target="#Modal'.$currentBook['id'].'-Modal"><div class="px-2 noWrap"><strong>Ver más</strong></div></button>
            </div>
            </div>
            </div>
            </div>
            </div>';
}

?>

</div>
</div>

<div id="newBooksModal">
    <?php

    $index = 0;

    $alreadyInArray = [];
    $unsetIndexes = [];

    $allBooks = $allBooks = array_merge($mostSold, $data[1]);

    for($i = 0; $i < Count($allBooks); $i++){
        if(!in_array($allBooks[$i]['id'], $alreadyInArray)){
            array_push($alreadyInArray, $allBooks[$i]['id']);
        }else{
            array_push($unsetIndexes, $i);
        }
    }

    foreach($unsetIndexes as $index){
        unset($allBooks[$index]);
    }

    foreach($allBooks as $currentBook){
        echo '<div class="modal fade" id="Modal'.$currentBook['id'].'-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">';

        if($currentBook['discount']) echo '<div class="bs-discount corner-badge right"><span><strong>-'.$currentBook['discount'].'%</strong></span></div>';

        echo'<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="theX" aria-hidden="true">&times;</span>
        </button>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-12 bookSlider">
                                <img class="previewImage centerHorizontal" alt="Portada del libro ' .$currentBook['title'].'" src="'.asset("images/uploads/".$currentBook['previewImage']).'" data-reminder="'.asset("images/uploads/".$currentBook['previewImage']).'">
                                <div id="modalSwiper'.$currentBook['id'].'" class="swiperBoxCostem">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">';

        foreach ($data[2] as $image){
            if($currentBook['images'] == $image['affiliationName']){
                echo'<div class="swiper-slide modalSwiperImg position-relative"><img class="previewSwiperImage" alt="Imagen de prevista del libro '.$currentBook['title'].'" data-src="'.asset('files/bookPreviews/'.$image['imgSrc']).'"></div>';
            }
        }

        echo'</div>
                                    </div>
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-12">
                                <div class="modalTextbox">
                                    <strong class="grey-color">
                                    '.$currentBook['category'].'
                                    </strong>
                                    <h2><strong>' .$currentBook['title'].'</strong></h2>
                                    <strong class="grey-color">' .$currentBook['author'].'</strong>
                                    <br>
                                    <br>
                                    <small class="modalTextStyle">
                                    ';

        if(strlen($currentBook['description']) > 0){
            echo '<strong>Presentación:</strong>
                                        <br>
                                        ' .$currentBook['description'].'
                                        <br>
                                        <br>';
        }

        echo '<strong>Descripción técnica del libro:</strong><br>';

        if(strlen($currentBook['measures']) > 0){
            echo $currentBook['measures'].'<br>';
        }

        if(strlen($currentBook['pages']) > 0){
            echo $currentBook['pages'].'<br>';
        }

        if(strlen($currentBook['language']) > 0){
            echo $currentBook['language'].'<br>';
        }

        echo 'ISBN/EAN: ' .$currentBook['isbn'].'<br>';

        if(strlen($currentBook['bookbinding']) > 0){
            echo $currentBook['bookbinding'].'<br>';
        }

        if(strlen($currentBook['edition']) > 0){
            echo $currentBook['edition'].'';
        }

        echo'</small>
                                </div>
                                <div class="row">
                                <div class="col-lg-6 col-md-5 col-12 mt-5">
                                    <span>Elegir Formato:</span>';
        if(isset($userData['library']) && in_array($currentBook['id'], $userData['library'])){
            echo '<button type="button" data-content="digital" class="btn optionSelector ml-2" data-redirect="'.url('/viewBook/'.$currentBook['title']).'" >Digital</button>';
        }else{
            echo '<button type="button" data-content="digital" class="btn optionSelector ml-2 selectedOption">Digital</button>';
        }
        echo'
                                    <button type="button" data-content="physical" class="btn optionSelector ';
        if(isset($userData['library']) && in_array($currentBook['id'], $userData['library'])) echo 'selectedOption';
        echo' ml-2" ';if($currentBook['stock'] < 1) echo 'disabled'; echo'>Físico</button>
                                </div>
                                <div class="col-lg-5 col-md-6 col-12 mt-3">
                                    <div class="grid-2-rows">
                                        <small><strong class="grey-color">Disponible</strong></small>
                                        <div role="group" aria-label="Selector de cantidad" class="input-group bookCounter">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="quant['.$index.']">
                                                <small>-</small>
                                            </button>
                                        </span>
                                        <input type="text" name="quant['.$index.']" class="form-control input-number" value="1" min="1" max="50" data-physicalMax="'.$currentBook['stock'].'">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant['.$index.']">
                                                <small>+</small>
                                            </button>
                                        </span>
                                    </div>
                                    <h2 class="modal-bookPrice"><strong class="price" data-physical="';

        if($currentBook['discount']){
            $amountToDiscount = ($currentBook['physicalPrice'] * $currentBook['discount'])/100;
            echo number_format(round($currentBook['physicalPrice']-$amountToDiscount, 2), 2);
        }else echo number_format($currentBook['physicalPrice'], 2);

        echo' €" data-digital="';

        if($currentBook['discount']){
            $amountToDiscount = ($currentBook['digitalPrice'] * $currentBook['discount'])/100;
            echo number_format(round($currentBook['digitalPrice']-$amountToDiscount, 2), 2);
        }else echo number_format($currentBook['digitalPrice'], 2);

        echo' €">';

        if(isset($userData['library']) && in_array($currentBook['id'], $userData['library'])){
            if($currentBook['discount']){
                $amountToDiscount = ($currentBook['physicalPrice'] * $currentBook['discount'])/100;
                echo number_format(round($currentBook['physicalPrice']-$amountToDiscount, 2), 2);
            }else echo number_format($currentBook['physicalPrice'], 2);
        }else{
            if($currentBook['discount']){
                $amountToDiscount = ($currentBook['digitalPrice'] * $currentBook['discount'])/100;
                echo number_format(round($currentBook['digitalPrice']-$amountToDiscount, 2), 2);
            }else echo number_format($currentBook['digitalPrice'], 2);
        }


        echo' €</strong></h2>
                                    <button type="button" data-dismiss="modal" class="btn btn-danger"><div class="px-4"><strong>Añadir</strong></div></button>
                                    ';

        if($currentBook['discount']){
            echo '<span id="discountDigital" class="text-muted text-lineThrough">'.number_format($currentBook['digitalPrice'], 2).'€</span>
                                              <span id="discountPhysical" class="text-muted text-lineThrough d-none">'.number_format($currentBook['physicalPrice'], 2).'€</span>
                                              <span id="discountBoth" class="text-muted text-lineThrough d-none">'.number_format(round($currentBook['digitalPrice']+$currentBook['physicalPrice'], 2), 2).'€</span>';
        }else echo '&nbsp';

        $printed = false;

        if(isset($userData['wishList'])){

            foreach ($userData['wishList'] as $wishedBook){
                if($wishedBook['bookId'] == $currentBook['id']){
                    echo '<a href="'.url('removeFromWishList/'.$currentBook['id']).'"><u class="red-color float-right mr-2"><small>Quitar de la lista de deseos</small></u></a>';
                    $printed = true;
                    break;
                }
            }
        }

        if(!$printed) echo '<a href="'.url('addToWishList/'.$currentBook['id']).'"><u class="red-color float-right mr-2"><small>Añadir a la lista de deseos</small></u></a>';
        echo'</div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script class="swiperNames" type="text/javascript">swiperNames.push("Modal'.$currentBook['id'].'-Modal")</script>';
        $index++;
    }
    ?>
</div>

<!-- E N D   C A T A L O G U E  -->

<!-- M O S T   S O L D -->
<h1 class="index-mostSoldBookTitle"><strong>Lo más vendido</strong></h1>
<section id="mostSoldBooKSwiper">
    <div class="container-fluid">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                foreach($mostSold as $currentBook){
                    echo '<div class="swiper-slide">
                            <div class="swiperHoverBox">
                                <img class="index-mostSoldBooKSwiperImg swiperHoverBoxBottom swiper-lazy" alt="Portada del libro '.$currentBook['title'].'" data-src="' .asset("images/uploads/".$currentBook['previewImage']).'" ><div class="swiper-lazy-preloader"></div>';

                                if($currentBook['discount']) echo '<div class="bs-discount tag tagAlternative right"><strong>-'.$currentBook['discount'].'%</strong></div>';

                                echo'
                                <div class="swiperHoverBoxTop">
                                    <div class="swiperHoverBoxAll">
                                        <div class="swiperHoverBoxHeader">
                                            <small>'.$currentBook['category'].'</small>
                                        </div>
                                        <div class="swiperHoverBoxTitle centerVertical">
                                            <strong>'.$currentBook['title']. '</strong>
                                        </div>
                                        <div class="swiperHoverBoxButton">
                                            <button type="button" class="btn btn-danger index-newBookButton seeMore" data-toggle="modal" data-target="#Modal' .$currentBook['id'].'-Modal"><div class="px-4"><strong class="noWrap">Ver más</strong></div></button>
                                        </div>
                                    </div>
	                            </div>
                            </div>
                          </div>';
                }
                ?>
            </div>
            <!-- A R R O W S -->
            <div class="swiperOverflowCoverRight"></div>
            <div class="swiper-button-next"></div>
            <div class="swiperOverflowCoverLeft"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!-- E N D   M O S T   S O L D -->

<script type="text/javascript" src="{{asset('js/catalogue.min.js')}}"></script>
@stop
@section('footer')
