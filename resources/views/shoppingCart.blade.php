@extends('layout')
@section('header')
@section('content')

@if(Session::has('paymentModal'))
    <!-- P A Y M E N T   M O D A L -->

    <div id="messageModal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal mediaPaymentModal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <form method="post" action="{{'validateInvoice'}}" >
                        {{csrf_field()}}
                        <div class="container">
                            <h2 class="pt-3"><span class="theX interactive float-right hoverRed closeUserAccess dt-2" data-dismiss="modal" aria-hidden="true">×</span></h2>
                            <ul class="nav nav-tabs nav-fill">
                                <li  class="nav-item border-0 h-auto"><a id="tabInvoice" class="nav-link black-color w-100 active" data-toggle="tab" href="#invoice">Factura</a></li>
                                <li class="nav-item border-0 h-auto"><a id="tabDirection" class="nav-link black-color w-100" data-toggle="tab" href="#direction">Dirección</a></li>
                                <li  class="nav-item border-0 h-auto"><a id="tabPayment" class="nav-link black-color w-100" data-toggle="tab" href="#payment">Pago</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="invoice" class="tab-pane fade in active show">
                                    <h2 class="text-center ml-3 mb-4 mt-3">
                                        <strong>Factura</strong>
                                    </h2>
                                    <div class="row">
                                        <p class="text-center px-lg-5 px-0 w-100">
                                            Compruebe que todo esté correcto:
                                        </p>
                                        <div class="col-12 px-lg-4 px-0">
                                            <div class="overflow-auto">
                                                <table summary="Factura" class="table table-bordered table-hover border">
                                                    <tbody>

                                                    <tr class="bg-slateGrey-color">
                                                        <td class="tcarext-left"><strong>Producto</strong></td>
                                                        <td><strong>Formato</strong></td>
                                                        <td><strong>Precio U.</strong></td>
                                                        <td><strong>Cantidad</strong></td>
                                                        <td><strong>Total</strong></td>
                                                    </tr>

                                                    <?php

                                                    foreach(Session::get('paymentModal')[0] as $invoice){
                                                        echo'<tr>
                                                    <td class="text-left">'.$invoice['name'].'</td>
                                                    <td>'.$invoice['format'].'</td>
                                                    <td>'.$invoice['price'].'€</td>
                                                    <td class="hoverShowAlert position-relative">';

                                                        echo $invoice['amount'];

                                                        if(isset($invoice['warning'])){
                                                            echo '<i class="glyphicon glyphicon-alert pl-2 red-color interactive"></i>
                                              <div class="position-absolute d-none bg-white-color shadow"><small class="red-color p-1"> '.$invoice['warning'].'</small></div>';
                                                        }

                                                        echo'</td>
                                        <td>'.$invoice['totalPrice'].'€</td>
                                        </tr>';
                                                    }

                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php

                                                if(Session::get('paymentModal')[3] != null){
                                                    echo '<p><strong>Cupón: </strong>'.Session::get('paymentModal')[3]['code'].'</p>
                                                          <p>Se ha aplicado un <strong>'.Session::get('paymentModal')[3]['discount'].'%</strong> de descuento ha su pedido.</p>';
                                                }
                                            ?>
                                            <p class="float-right pt-3"><strong>Total (<?php echo Session::get('paymentModal')[1][4] ?> Incluido): </strong> <?php  echo Session::get('paymentModal')[2] ?>€</p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger mt-3 mb-2 float-right triggerTabDirection"><strong>Siguiente</strong></button>
                                </div>
                                <div id="direction" class="tab-pane fade">
                                    <h2 class="text-center ml-3 mb-4 mt-3">
                                        <strong>Dirección*</strong>
                                    </h2>
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <div class="border p-4">
                                                <h5 class="text-center mb-3">Dirección de Envio</h5>
                                                <div class="mb-2">
                                                    <strong>Nombre:</strong>
                                                    <input class="form-control" name="shipmentName" value="<?php echo Session::get('paymentModal')[1][0] ?>">
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Apellidos:</strong>
                                                    <input class="form-control" name="shipmentSurnames"  value="<?php echo Session::get('paymentModal')[1][1] ?>">
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Dirección:</strong>
                                                    <textarea class="form-control" name="shipmentAddress" rows="3"><?php echo Session::get('paymentModal')[1][2] ?></textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>CP:</strong>
                                                    <input class="form-control" name="shipmentPostCode" value="<?php echo Session::get('paymentModal')[1][3] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class=" border p-4">
                                                <h5 class="text-center mb-3">Dirección de Facturación</h5>
                                                <div class="mb-2">
                                                    <strong>Nombre:</strong>
                                                    <input class="form-control" name="billingName" value="<?php echo Session::get('paymentModal')[1][0] ?>">
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Apellidos:</strong>
                                                    <input class="form-control" name="billingSurnames" value="<?php echo Session::get('paymentModal')[1][1] ?>">
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Dirección:</strong>
                                                    <textarea class="form-control red" name="billingAddress" rows="3"><?php echo Session::get('paymentModal')[1][2] ?></textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>CP:</strong>
                                                    <input class="form-control" name="billingPostCode" value="<?php echo Session::get('paymentModal')[1][3] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="pt-3 pl-3"><small class="text-muted">*Alterar la dirección puede cambiar el impuesto aplicado a su compra.</small></p>
                                    </div>
                                    <button type="button" class="btn btn-danger mt-2 mb-2 ml-1 triggerTabInvoice"><strong>Anterior</strong></button>
                                    <button type="button" class="btn btn-danger mt-2 mb-2 float-right triggerTabPayment"><strong>Siguiente</strong></button>
                                </div>
                                <div id="payment" class="tab-pane fade px-lg-5 px-0">
                                    <h2 class="text-center ml-3 mb-4 mt-3">
                                        <strong>Pago*</strong>
                                    </h2>
                                    <div class="container">
                                        <div class="row">
                                            <div class="w-100">
                                                <div class="form-group">
                                                    <label for="confirmPaymentPass">Contraseña</label>
                                                    <input type="password" class="form-control" id="confirmPaymentPass" name="password" aria-describedby="Contraseña" required>
                                                    <small id="confirmPaymentPass" class="form-text text-muted">Escriba su contraseña para confirmar su compra.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p><strong>Total (<?php echo Session::get('paymentModal')[1][4] ?> Incluido): </strong> <?php echo Session::get('paymentModal')[2] ?>€</p>
                                    <p class="text-muted">
                                        <small>
                                            * Editorial Paralelo28 comunica al titular de la tarjeta que éste es responsable de las transacciones.
                                            Recuerde que los datos se introducen en una página segura y se transfieren sobre una conexión cifrada
                                            <a href="https://es.wikipedia.org/wiki/Transport_Layer_Security">SSL</a>.
                                        </small>
                                    </p>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="acceptTermsAndConditions" name="acceptTermsAndConditions" required>
                                        <label class="form-check-label" for="acceptTermsAndConditions">
                                            He leído y acepto los <a href="{{url('termsAndConditions')}}">Terminos y condiciones</a> y la
                                            <a href="{{url('shipmentsAndRefundsPolicy')}}">política de envíos y devoluciones</a>.
                                        </label>
                                    </div>
                                    <input type="hidden" name="invoiceData" value='<?php echo json_encode(Session::get('paymentModal')[0]) ?>'>
                                    <?php

                                    if(Session::get('paymentModal')[3] != null){
                                        echo '<input type="hidden" name="coupon" value='.Session::get('paymentModal')[3]['code'].'>';
                                    }
                                    
                                    ?>
                                    <button type="button" class="btn btn-danger mt-4 mb-2 ml-1 triggerTabDirection"><strong>Anterior</strong></button>
                                    <button type="submit" class="btn btn-danger mt-4 mb-2 float-right"><strong>Finalizar compra</strong></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script id="messageScript" type="text/javascript">
        $('#messageModal').modal('toggle');
        $('#messageScript').remove();
    </script>

    <!-- E N D   P A Y M E N T   M O D A L -->

@endif

@if(Session::has('redirectTPV'))

    <!-- T P V   R E D I R E C T -->

    <div id="confirmRedirectModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content overflow-auto">
                <div class="modal-body">
                    <h2 class="text-center ml-4 mb-4">
                        <strong>Aviso de Redirección</strong>
                        <span class="theX interactive float-right hoverRed closeUserAccess" data-dismiss="modal" aria-hidden="true">×</span>
                    </h2>
                    <div class="row"><p class="text-center pb-2 w-100"><strong>Haz click aquí si la redirección esta tardando demasiado.</strong></p></div>
                    <form id="tpvRedirectForm" name="from" method="post" action="{{Session::get('redirectTPV')[0]}}">
                        <input type="hidden" name="Ds_SignatureVersion" value="{{Session::get('redirectTPV')[1]}}"/>
                        <input type="hidden" name="Ds_MerchantParameters" value="{{Session::get('redirectTPV')[2]}}"/>
                        <input type="hidden" name="Ds_Signature" value="{{Session::get('redirectTPV')[3]}}"/>
                        <button class="btn btn-danger centerHorizontal" type="submit">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script id="confirmRedirectScript" type="text/javascript">
        $('#tpvRedirectForm').submit();
        $('#confirmRedirectModal').modal('toggle');
        $('#confirmRedirectScript').remove();
    </script>

    <!-- E N D   T P V   R E D I R E C T -->

@endif

<!-- S H O P P I N G   C A R T -->
<h1 class="index-mostSoldBookTitle mediaShoppingCartTitle mt-5"><strong>Carrito de la compra</strong></h1>
<section id="shoppingCartFull">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="emptyCart">
                    <h5 class="text-center"><strong>Su carro esta vacío</strong></h5>
                    <h6 class="text-center">Cuando añada un libro, aparecerá aquí</h6>
                    <img class="shoppingCartImage centerHorizontal" alt="Imagen de un carrito vacío." src="{{asset('images/cartEmpty.png')}}">
                    <a href="{{url('/')}}"><button class="btn btn-danger centerHorizontal noWrap"><strong>Seguir comprando</strong></button></a>
                </div>
                <div id="shoppingCartFullItems"></div>
            </div>
            <div class="col-lg-3 col-12">
                <form method="post" class="paymentForm" action="{{url('finalizePayment')}}">
                    {{csrf_field()}}
                    <input id="purchaseData" type="hidden" name="purchaseData">
                    <div class="coupon">
                        <strong>Cupón</strong>
                        <input class="text-center" type="text" name="coupon">
                    </div>
                    <div class="saleOverview">
                        <p><strong>Subtotal</strong><span>0,00€</span></p>
                        <p><strong>Impuesto(<?php if($userData){
                                    echo $userData['taxName'];
                                }else{
                                    echo 'IGIC';
                                } ?>)</strong><span>0,00€</span></p>
                        <p><strong>Total</strong><span>0,00€</span></p>
                        <button type="submit" class="btn btn-danger centerHorizontal noWrap"><strong>Finalizar Compra</strong></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- E N D   S H O P P I N G   C A R T -->


<!-- R E C O M M E N D A T I O N S -->
<h1 class="index-mostSoldBookTitle mt-6"><strong>Recomendaciones</strong></h1>
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

<div id="newBooksModal">
    <?php
    $index = 0;
    foreach($mostSold as $currentBook){
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

        foreach ($data as $image){
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
<!-- E N D   R E C O M M E N D A T I O N S -->
@stop
@section('footer')
