@extends('layout')
@section('header')
@section('content')

<!-- S E A R C H   R E S U L T S -->

<div class="container-fluid mt-5 mediaSearch">
    <h1 class="text-center mb-6"><strong>Búsqueda </strong><?php echo $data[0]; ?></h1>

    <?php

    foreach ($data[1] as $searchResult){
        if($searchResult['title']){
            echo '<div class="row mb-6">
            <div class="col-12 ">
            <div class="row index-newBookBox">
            <div class="col-2">
            <img class="index-newBookImg" alt="Portada del libro ' .$searchResult['title'].'" src="'.asset("images/uploads/".$searchResult['previewImage']).'">
            </div>
            <div class="col-8 index-newBookText">
            ' .$searchResult["category"].'
            <br>
            <h4 class="bookTitle"><strong> '.$searchResult["title"]. '</strong></h4>
            <p class="text-secondary">'.$searchResult["author"].'</p>
            </div>
            <div class="col-2">';
            if($searchResult['discount']) echo '<div class="bs-discount tag right"><strong>-'.$searchResult['discount'].'%</strong></div>';
            echo'
            <div class="centerVertical centerInBookBox">
            <h1><strong> '.$searchResult["digitalPrice"].'€</strong></h1>
            <button type="button"  class="btn btn-danger seeMore" data-toggle="modal" data-target="#Modal'.$searchResult['id'].'-Modal"><div class="px-4"><strong>Ver más</strong></div></button>
            </div>
            </div>
            </div>
            </div>
            </div>';
        }else{
            echo '<div class="row mb-6">
            <div class="col-12 ">
            <div class="row index-newBookBox">
            <div class="col-2">
            <img class="index-newBookImg" alt="Portada del libro ' .$searchResult['certificate'].'" src="'.asset("images/uploads/".$searchResult['imageLink']).'">
            </div>
            <div class="col-8 index-newBookText">
            '  .$searchResult["category"].'
            <br>
            <h4 class="bookTitle"><strong> ' .$searchResult["certificate"]. '</strong></h4>
            </div>
            <div class="col-2">
            <div class="centerVertical centerInBookBox">
            <a class="d-block anchor" href="'.url('/catalogue/certificate/'.$searchResult["certificate"]).'?key='.$searchResult["id"]. '"><button type="button" class="btn btn-danger index-newBookButton seeMore"><div class="px-4"><strong>Ver más</strong></div></button></a>
            </div>
            </div>
            </div>
            </div>
            </div>';
        }
    }

    ?>
</div>

<!-- E N D   S E A R C H   R E S U L T S -->

<!-- M O D A L S -->

<div id="newBooksModal">
<?php

$index = 0;

foreach($data[1] as $searchResult){
    if($searchResult['title']){
        echo '<div class="modal fade" id="Modal'.$searchResult['id'].'-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">';

        if($searchResult['discount']) echo '<div class="bs-discount corner-badge right"><span><strong>-'.$searchResult['discount'].'%</strong></span></div>';

        echo'<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="theX" aria-hidden="true">&times;</span>
        </button>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-12 bookSlider">
                                <img class="previewImage centerHorizontal" alt="Portada del libro ' .$searchResult['title'].'" src="'.asset("images/uploads/".$searchResult['previewImage']).'" data-reminder="'.asset("images/uploads/".$searchResult['previewImage']).'">
                                <div id="modalSwiper'.$searchResult['id'].'" class="swiperBoxCostem">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">';

        foreach ($data[2] as $image){
            if($searchResult['images'] == $image['affiliationName']){
                echo'<div class="swiper-slide modalSwiperImg position-relative"><img class="previewSwiperImage" alt="Imagen de prevista del libro '.$searchResult['title'].'" data-src="'.asset('files/bookPreviews/'.$image['imgSrc']).'"></div>';
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
                                    '.$searchResult['category'].'
                                    </strong>
                                    <h2><strong>' .$searchResult['title'].'</strong></h2>
                                    <strong class="grey-color">' .$searchResult['author'].'</strong>
                                    <br>
                                    <br>
                                    <small class="modalTextStyle">
                                    ';

        if(strlen($searchResult['description']) > 0){
            echo '<strong>Presentación:</strong>
                                        <br>
                                        ' .$searchResult['description'].'
                                        <br>
                                        <br>';
        }

        echo '<strong>Descripción técnica del libro:</strong><br>';

        if(strlen($searchResult['measures']) > 0){
            echo $searchResult['measures'].'<br>';
        }

        if(strlen($searchResult['pages']) > 0){
            echo $searchResult['pages'].'<br>';
        }

        if(strlen($searchResult['language']) > 0){
            echo $searchResult['language'].'<br>';
        }

        echo 'ISBN/EAN: ' .$searchResult['isbn'].'<br>';

        if(strlen($searchResult['bookbinding']) > 0){
            echo $searchResult['bookbinding'].'<br>';
        }

        if(strlen($searchResult['edition']) > 0){
            echo $searchResult['edition'].'';
        }

        echo'</small>
                                </div>
                                <div class="row">
                                <div class="col-lg-6 col-md-5 col-12 mt-5">
                                    <span>Elegir Formato:</span>';
        if(isset($userData['library']) && in_array($searchResult['id'], $userData['library'])){
            echo '<button type="button" data-content="digital" class="btn optionSelector ml-2" data-redirect="'.url('/viewBook/'.$searchResult['title']).'" >Digital</button>';
        }else{
            echo '<button type="button" data-content="digital" class="btn optionSelector ml-2 selectedOption">Digital</button>';
        }
        echo'
                                    <button type="button" data-content="physical" class="btn optionSelector ';
        if(isset($userData['library']) && in_array($searchResult['id'], $userData['library'])) echo 'selectedOption';
        echo' ml-2" ';if($searchResult['stock'] < 1) echo 'disabled'; echo'>Físico</button>
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
                                        <input type="text" name="quant['.$index.']" class="form-control input-number" value="1" min="1" max="50" data-physicalMax="'.$searchResult['stock'].'">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant['.$index.']">
                                                <small>+</small>
                                            </button>
                                        </span>
                                    </div>
                                    <h2 class="modal-bookPrice"><strong class="price" data-physical="';

        if($searchResult['discount']){
            $amountToDiscount = ($searchResult['physicalPrice'] * $searchResult['discount'])/100;
            echo number_format(round($searchResult['physicalPrice']-$amountToDiscount, 2), 2);
        }else echo number_format($searchResult['physicalPrice'], 2);

        echo' €" data-digital="';

        if($searchResult['discount']){
            $amountToDiscount = ($searchResult['digitalPrice'] * $searchResult['discount'])/100;
            echo number_format(round($searchResult['digitalPrice']-$amountToDiscount, 2), 2);
        }else echo number_format($searchResult['digitalPrice'], 2);

        echo' €">';

        if(isset($userData['library']) && in_array($searchResult['id'], $userData['library'])){
            if($searchResult['discount']){
                $amountToDiscount = ($searchResult['physicalPrice'] * $searchResult['discount'])/100;
                echo number_format(round($searchResult['physicalPrice']-$amountToDiscount, 2), 2);
            }else echo number_format($searchResult['physicalPrice'], 2);
        }else{
            if($searchResult['discount']){
                $amountToDiscount = ($searchResult['digitalPrice'] * $searchResult['discount'])/100;
                echo number_format(round($searchResult['digitalPrice']-$amountToDiscount, 2), 2);
            }else echo number_format($searchResult['digitalPrice'], 2);
        }


        echo' €</strong></h2>
                                    <button type="button" data-dismiss="modal" class="btn btn-danger"><div class="px-4"><strong>Añadir</strong></div></button>
                                    ';

        if($searchResult['discount']){
            echo '<span id="discountDigital" class="text-muted text-lineThrough">'.number_format($searchResult['digitalPrice'], 2).'€</span>
                                              <span id="discountPhysical" class="text-muted text-lineThrough d-none">'.number_format($searchResult['physicalPrice'], 2).'€</span>
                                              <span id="discountBoth" class="text-muted text-lineThrough d-none">'.number_format(round($searchResult['digitalPrice']+$searchResult['physicalPrice'], 2), 2).'€</span>';
        }else echo '&nbsp';

        $printed = false;

        if(isset($userData['wishList'])){

            foreach ($userData['wishList'] as $wishedBook){
                if($wishedBook['bookId'] == $searchResult['id']){
                    echo '<a href="'.url('removeFromWishList/'.$searchResult['id']).'"><u class="red-color float-right mr-2"><small>Quitar de la lista de deseos</small></u></a>';
                    $printed = true;
                    break;
                }
            }
        }

        if(!$printed) echo '<a href="'.url('addToWishList/'.$searchResult['id']).'"><u class="red-color float-right mr-2"><small>Añadir a la lista de deseos</small></u></a>';
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
    <script class="swiperNames" type="text/javascript">swiperNames.push("Modal'.$searchResult['id'].'-Modal")</script>';
        $index++;
    }
    }

    ?>
</div>

<!-- E N D   M O D A L S -->

@stop
@section('footer')
