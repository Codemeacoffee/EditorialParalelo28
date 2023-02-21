@extends('layout')
@section('header')
@section('content')

@if(Session::has('emptyCart'))

    <script id="emptyCartScript" type="text/javascript">
        $(document).ready(function () {
            localStorage.removeItem('cartItems')
            cartContent = [];
            printShoppingCartItems($('#shoppingCartItems'), ['col-lg-3 col-md-4 col-5', 'col-lg-6 col-md-5 col-4', 'col-3']);
            $('#emptyCartScript').remove();
        });
    </script>

@endif

<!-- H O M E -->
<div class="container-fluid mt-5 mediaHome">
    <h1 class="text-center"><strong>Bienvenido </strong><?php echo $userData['name']; ?></h1>
    
   <?php

    if(Count($data[5]) > 0){
        echo '<div class="col-lg-9 offset-lg-3 col-12 offset-0 mt-5 px-5 py-4">
            <h6 class="mb-3 text-danger"><strong>Confirme la llegada de los siguientes pedidos cuandos sean recibidos:</strong></h6>';
        foreach ($data[5] as $sendShipment) echo'<a href="'.url('shipment/'.$sendShipment).'" class="noUnderline"><p>Pedido número '.$sendShipment.'</p></a>';
        echo'</div>';
    }

    ?>

    <div class="row mt-6">
        <div class="col-lg-3 col-12">
            <div id="homeOptions" class="stickyBox">
                <p id="selected" class="px-2"><strong class="interactive">Biblioteca</strong></p>
                <p class="px-2"><strong class="grey-color interactive hoverRed">Lista de deseos</strong></p>
                <p class="px-2"><strong class="grey-color interactive hoverRed">Historial de compras</strong></p>
                <p class="px-2"><strong class="grey-color interactive hoverRed">Configuración de cuenta</strong></p>
                <?php
                if($userData['admin'] > 0) echo '<p class="px-2"><strong class="grey-color interactive hoverRed"><a class=" text-decoration-none grey-color hoverRed" href="'.url('admin').'">Mi cuenta de administrador</a></strong></p>';
                ?>
                <p class="px-2"><strong><u><a href="{{url('closeSession')}}" class="red-color hoverRed">Cerrar sesión</a></u></strong></p>
            </div>
            <div id="homeOptionsMobile" class="d-none w-100">
                <select class="form-control">
                    <option value="0">Biblioteca</option>
                    <option value="1">Lista de deseos</option>
                    <option value="2">Historial de compras</option>
                    <option value="3">Configuración de cuenta</option>
                    <?php
                    if($userData['admin'] > 0) echo '<option value="'.url('admin').'">Mi cuenta de administrador</option>';
                    ?>
                    <option value="{{url('closeSession')}}">Cerrar sesión</option>
                </select>
            </div>
        </div>
        <div class="col-lg-9 col-12">

            <!-- L I B R A R Y -->

            <div id="library" class="grid-2-rows">
                <?php
                    foreach ($data[0] as $book){
                        echo '<div class="col-12 mb-6">
                        <div class="row index-newBookBox">
                        <div class="col-6 index-newBookText">
                        '.$book['category'].'
                        <br>
                        <h4><strong> '.$book["title"]. '</strong></h4>
                        <br>
                        <a href="'.url('viewBook/'.$book['title']). '"><button type="button"  class="btn btn-danger index-newBookButton seeMore"><div class="px-4"><strong>Leer</strong></div></button></a>
                        </div>
                        <div class="col-6">
                        <img class="index-newBookImg" alt="Portada del libro ' .$book['title'].'" src="'.asset("images/uploads/".$book['previewImage']).'">
                        </div>
                        </div>
                        </div>';
                    }
                ?>
            </div>

            <!-- E N D   L I B R A R Y -->

            <!-- W I S H   L I S T -->

            <div id="wishList" class="grid-2-rows displayNone">
                <?php
                foreach ($data[1] as $book){
                    echo '<div class="col-12 mb-6">
                        <div class="row index-newBookBox">
                        <div class="col-6 index-newBookText">
                        '.$book['category'].'
                        <br>
                        <h4><strong> '.$book["title"]. '</strong></h4>
                        <br>
                        <button type="button"  class="btn btn-danger index-newBookButton seeMore" data-toggle="modal" data-target="#Modal' .$book['id'].'-Modal"><div class="px-4"><strong>Ver más</strong></div></button>
                        </div>
                        <div class="col-6">
                            <img class="index-newBookImg" alt="Portada del libro ' .$book['title'].'" src="'.asset("images/uploads/".$book['previewImage']).'">';
                        if($book['discount']) echo '<div class="bs-discount tag right"><strong>-'.$book['discount'].'%</strong></div>';
                        echo'
                        </div>
                        </div>
                        </div>';
                }

                ?>
            </div>

            <!-- E N D   W I S H   L I S T -->

            <!-- S H O P P I N G   H I S T O R Y -->

            <div id="shoppingHistory" class=" displayNone">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-xs-12">
                            <div class="row mx-0 pb-2 border-bottom-red">
                                <div class="col-3">Código</div>
                                <div class="col-3">Fecha</div>
                                <div class="col-2">Estado</div>
                                <div class="col-2">Precio total</div>
                                <div class="col-2">Detalles</div>
                            </div>
                                <div id="accordion">
                                    <?php

                                    foreach ($data[2][0] as $record){
                                        $dateParts = explode('-', explode(' ', $record['created_at'])[0]);
                                        $date = $dateParts[2].'/'.$dateParts[1].'/'.$dateParts[0];

                                        $statusColor = 'red-color';

                                        if($record['status'] == 'Entregado') $statusColor = 'green-color';

                                        echo'<div class="shoppingHistoryHead d-flex">
                                        <div class="row w-100">
                                        <div class="col-3">'.$record['shipmentCode'].'</div>
                                        <div class="col-3">'.$date.'</div>
                                        <div class="col-2"><strong class="'.$statusColor.'">'.$record['status'].'</strong></div>
                                        <div class="col-2">'.$record['price'].'€</div>
                                        <div class="col-2"><a class="black-color hoverRed" href="'.url('shipment/'.$record['shipmentCode']).'" target="_blank">Ver más</a></div>
                                        </div></div><div class="px-0">';
                                        foreach ($data[2][1] as $subRecord){
                                            if($subRecord['shipmentCode'] == $record['shipmentCode']){
                                                echo '
                                                      <div class="row w-100 mx-0 pt-2">
                                                      <div class="col-8 text-overflow-ellipsis">'.$subRecord['title'].'</div>
                                                      <div class="col-2 text-overflow-ellipsis">'.$subRecord['price'].'€</div>
                                                      <div class="col-2 text-overflow-ellipsis">';

                                                        if($subRecord['option'] == 0) echo 'Físico';
                                                        else echo 'Digital';

                                                      echo'</div></div>';
                                            }
                                        }
                                        echo'</div>';
                                    }

                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- E N D   S H O P P I N G   H I S T O R Y -->

            <!-- A C C O U N T   S E T T I N G S -->

            <div id="accountSettings" class="displayNone">
                <p><strong>Información de cuenta</strong></p>
                <?php
                    if($data[3]['name']) echo '<p>'.$data[3]['name'].'</p>';
                    if($data[3]['surnames']) echo '<p>'.$data[3]['surnames'].'</p>';
                    if($userData['email']) echo '<p>'.$userData['email'].'</p>';
                ?>
                <u><a id="editAccountInfo" class="red-color interactive">Editar</a></u>
                <p class="mt-5"><strong>Dirección</strong></p>
                <?php
                    if($data[3]['direction']){
                        echo '<p>'.$data[3]['direction'].'</p>
                        <script id="accountDirectionComplete" type="text/javascript">
                            $(document).ready(function () {
                                accountDirectionComplete = "'.$data[3]['direction'].'";
                                $("#accountDirectionComplete").remove();
                            });
                        </script>';
                    };
                    if($data[3]['postalCode']){
                        echo '<p>'.$data[3]['postalCode'].'</p>
                        <script id="accountPostalCodeComplete" type="text/javascript">
                            $(document).ready(function () {
                                accountPostalCodeComplete = "'.$data[3]['postalCode'].'";
                                $("#accountPostalCodeComplete").remove();
                            });
                        </script>';
                    };
                ?>
                <u><a id="editAccountDirection" class="red-color interactive">Editar</a></u>
                <p class="mt-5"><strong>Tipo de cuenta</strong></p>
                <p><?php if($userData['accountType'] == 0) echo 'Particular'; else echo 'Empresarial'; ?></p>
                <u><a id="editAccountType" class="red-color interactive">Editar</a></u>
                <p class="mt-5"><strong>Ajustes avanzados</strong></p>
                <u><a id="editAccountDeepSettings" class="red-color interactive">Editar</a></u>
            </div>

            <!-- E N D   A C C O U N T   S E T T I N G S -->

            <!-- E D I T   A C C O U N T   I N F O   F O R M-->

            <div id="accountInfoBlock" class="displayNone">
                <form id="accountInfoForm" method="POST" action="{{url('editAccountInfo')}}">
                    {{csrf_field()}}
                    <div class="grid-2-rows mb-4">
                        <div class="pr-5 form-group">
                            <label for="userName"><strong class="grey-color">Nombre*</strong></label>
                            <input id="userName" type="text" class="form-control" name="userName" <?php  if($userData['name']) echo 'value="'.$userData['name'].'"'; ?>  required>
                        </div>
                        <div class="pr-5 form-group">
                            <label for="userSurname"><strong class="grey-color">Apellidos*</strong></label>
                            <input id="userSurname" type="text" class="form-control" name="userSurname" <?php  if($data[3]['surnames']) echo 'value="'.$data[3]['surnames'].'"'; ?> required>
                        </div>
                    </div>
                    <div class="pr-5 form-group">
                        <label for="userEmail"><strong class="grey-color">Email*</strong></label>
                        <input id="userEmail" type="email" class="form-control" name="userEmail"  <?php  if($userData['email']) echo 'value="'.$userData['email'].'"'; ?> required>
                        <small class="text-danger formError">&nbsp;</small>
                    </div>
                    <div class="pr-5 form-group">
                        <label for="oldPass"><strong class="grey-color">Contraseña</strong></label>
                        <input id="oldPass" type="password" name="oldPass" class="form-control" autocomplete="off">
                        <small class="text-danger formError">&nbsp;</small>
                    </div>
                    <div class="pr-5 form-group">
                        <label for="newPass"><strong class="grey-color">Nueva Contraseña</strong></label>
                        <input id="newPass" type="password" name="newPass" class="form-control" autocomplete="off">
                        <small class="text-danger formError">&nbsp;</small>
                    </div>
                    <div class="pr-5 form-group">
                        <label for="newPassConfirm"><strong class="grey-color">Confirmar Nueva Contraseña</strong></label>
                        <input id="newPassConfirm" type="password" name="newPassConfirm" class="form-control" autocomplete="off">
                        <small class="text-danger formError">&nbsp;</small>
                    </div>
                    <div class="grid-2-rows mobile2rows mt-5">
                        <div class="pr-5 form-group mt-2">
                            <strong><u><a class="red-color interactive backToAccountConfig">Volver</a></u></strong>
                        </div>
                        <div class="pr-5 form-group text-right">
                            <button type="submit" class="btn btn-danger"><strong>Guardar</strong></button>
                        </div>
                    </div>
                </form>
            </div>


            <!-- E N D   E D I T   A C C O U N T   I N F O   F O R M-->

            <!-- E D I T   A C C O U N T   D I R E C T I O N   F O R M-->

            <div id="accountDirectionBlock" class="displayNone">
                <div class="grid-2-rows">
                    <form id="accountDirectionForm">
                        <div class="pr-5 form-group">
                            <label for="provinceSelect"><strong class="grey-color">Provincia*</strong></label>
                            <select id="provinceSelect" class="custom-select" required></select>
                        </div>
                        <div class="pr-5 form-group">
                            <label for="townshipSelect"><strong class="grey-color">Municipio*</strong></label>
                            <select id="townshipSelect" class="custom-select" required></select>
                        </div>
                        <div class="pr-5 form-group">
                            <label for="residentialDirection"><strong class="grey-color">Calle*</strong></label>
                            <input id="residentialDirection" type="text" class="form-control" required>
                        </div>
                        <div class="grid-3-rows">
                            <div class="pr-5 form-group">
                                <label for="residentialNumber"><strong class="grey-color">Número</strong></label>
                                <input id="residentialNumber" type="number" class="form-control">
                            </div>
                            <div class="pr-5 form-group">
                                <label for="residentialExtraInfo"><strong class="grey-color noWrap">Otros (Especificar)</strong></label>
                                <input id="residentialExtraInfo" type="text" class="form-control">
                            </div>
                            <div class="pr-5 form-group"><br/>
                                <button id="validateDirection" class="btn btn-danger w-100 mt-2"><strong>Validar</strong></button>
                            </div>
                        </div>
                        <div class="grid-2-rows mobile2rows absoluteToBottom">
                            <div class="pr-5 form-group mt-2">
                                <strong><u><a class="red-color interactive backToAccountConfig">Volver</a></u></strong>
                            </div>
                            <div class="pr-5 form-group text-right">
                                <button id="saveDirection" type="button" class="btn btn-danger disabled"><strong>Guardar</strong></button>
                            </div>
                        </div>
                    </form>
                    <div id="accountDirection" class="position-relative">
                        <div class="userWaitForIframeLayer">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="-200 -200 500 500" preserveAspectRatio="xMidYMid" class="lds-rolling">
                                <circle cx="50" cy="50" fill="none"   stroke="#ff0000" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138" transform="rotate(188.805 50 50)">
                                    <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="2s" begin="0s" repeatCount="indefinite"></animateTransform>
                                </circle>
                            </svg>
                            <div class="layerErrorMessage h-100 position-relative overflow-hidden">
                                <div class="absoluteCenterBoth text-center">
                                    <h3>Oops..</h3>
                                    <h4>Ha ocurrido un error</h4>
                                    <p><strong>Revise que sus datos esten correctos.</strong></p>
                                </div>
                            </div>
                        </div>
                        <form id="confirmDirectionForm" method="POST" action="{{url('editAccountDirection')}}">
                            {{csrf_field()}}
                            <div class="pr-5 form-group">
                                <label for="parsedDirection"><strong class="grey-color">Dirección*</strong></label>
                                <input id="parsedDirection" type="text" class="form-control" name="direction" required readonly>
                            </div>
                            <div class="pr-5 form-group">
                                <label for="postalCode"><strong class="grey-color">Código Postal*</strong></label>
                                <input id="postalCode" type="number" class="form-control" name="postalCode" required>
                            </div>
                        </form>
                        <div>
                            <iframe id="userDirectionMap" class="mt-3 pr-5 w-100" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- E N D   E D I T   A C C O U N T   D I R E C T I O N   F O R M-->

            <!-- E D I T   A C C O U N T   T Y P E -->

            <div id="accountTypeBlock" class="displayNone">
                <form id="accountTypeForm" method="POST" action="{{url('editTypeInfo')}}">
                    {{csrf_field()}}
                    <div class="pr-5 form-group">
                        <label for="accountTypeSelect"><strong class="grey-color">Tipo de Cuenta*</strong></label>
                        <select id="accountTypeSelect" class="custom-select" name="accountType" required>
                            <option value="0" <?php if($userData['accountType'] == 0) echo 'selected' ?>>Particular</option>
                            <option value="1" <?php if($userData['accountType'] == 1) echo 'selected' ?>>Empresarial</option>
                        </select>
                    </div>
                    <div class="grid-2-rows mb-4">
                        <div class="pr-5 form-group">
                            <label for="companyName"><strong class="grey-color">Nombre de la Empresa*</strong></label>
                            <input id="companyName" type="text" class="form-control" name="companyName" <?php echo 'value="'.$userData['companyName'].'"'; if(strlen($userData['companyName']) == 0) echo 'disabled'; ?> required>
                        </div>
                        <div class="pr-5 form-group">
                            <label for="companyCIF"><strong class="grey-color">CIF*</strong></label>
                            <input id="companyCIF" type="text" class="form-control" name="companyCIF" <?php echo 'value="'.$userData['companyCIF'].'"'; if(strlen($userData['companyCIF']) == 0) echo 'disabled'; ?> required>
                        </div>
                    </div>
                    <div class="grid-2-rows mobile2rows mt-5">
                        <div class="pr-5 form-group mt-2">
                            <strong><u><a class="red-color interactive backToAccountConfig">Volver</a></u></strong>
                        </div>
                        <div class="pr-5 form-group text-right">
                            <button type="submit" class="btn btn-danger"><strong>Guardar</strong></button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- E N D   E D I T   A C C O U N T  T Y P E -->

            <!-- E D I T   A C C O U N T   D E E P   S E T T I N G S -->

            <div id="accountDeepSettingsBlock" class="displayNone">
                <form method="POST" action="{{url('editDeepSettings')}}">
                    {{csrf_field()}}
                    <div class="row mx-0">
                        <div class="col-lg-6 col-12">
                            <div class="pr-5 form-group">
                                <label for="maxInactiveTime"><strong class="grey-color">Tiempo máximo de inactividad*</strong></label>
                                <select id="maxInactiveTime" class="custom-select" name="maxInactiveTime" required>
                                    <option value="0" <?php if($userData['expire'] == 0) echo 'selected' ?>>5 minutos</option>
                                    <option value="1" <?php if($userData['expire'] == 1) echo 'selected' ?>>15 minutos</option>
                                    <option value="2" <?php if($userData['expire'] == 2) echo 'selected' ?>>30 minutos</option>
                                    <option value="3" <?php if($userData['expire'] == 3) echo 'selected' ?>>Ilimitado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="pr-5 form-group">
                                <label for="rememberMe"><strong class="grey-color">Recuerdame*</strong></label>
                                <select id="rememberMe" class="custom-select" name="rememberMe" required>
                                    <option value="0" <?php if($userData['remember_me'] == 0) echo 'selected' ?>>Nunca</option>
                                    <option value="1" <?php if($userData['remember_me'] == 1) echo 'selected' ?>>1 día</option>
                                    <option value="2" <?php if($userData['remember_me'] == 2) echo 'selected' ?>>1 semana</option>
                                    <option value="3" <?php if($userData['remember_me'] == 3) echo 'selected' ?>>Siempre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="grid-2-rows mobile2rows mt-5">
                        <div class="pr-5 form-group mt-2">
                            <strong><u><a class="red-color interactive backToAccountConfig">Volver</a></u></strong>
                        </div>
                        <div class="pr-5 form-group text-right">
                            <button type="submit" class="btn btn-danger"><strong>Guardar</strong></button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- E N D   E D I T   A C C O U N T   D E E P   S E T T I N G S -->

            <!-- M O D A L S -->

            <div id="newBooksModal">
                <?php
                $index = 0;
                foreach($data[1] as $currentBook){
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

        foreach ($data[4] as $image){
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

            <!-- E N D   M O D A L S -->

        </div>
    </div>
</div>

<!-- E N D   H O M E -->

<script type="text/javascript" src="{{asset('js/home.min.js')}}" defer></script>

@stop
@section('footer')
