@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N I S T R A T E   S H I P M E N T -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Pedido número {{$data[0]['shipmentCode']}}</strong></h1>

    <form action="{{url('updateShipment/'.$data[0]['shipmentCode'])}}" method="post">
        {{csrf_field()}}
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-12">
                <div class="container-fluid px-lg-5 px-0">
                    <div class="row mt-6 position-relative">
                        <div class="col-lg-6 col-12 mb-5">
                            <div class="mb-2">
                                <strong>Estado:</strong>
                                <select class="form-control mt-2" name="status">
                                    <option value="Pagado" <?php if($data[0]['status'] == 'Pagado') echo 'selected="true"'?>>Pagado</option>
                                    <option value="Enviado" <?php if($data[0]['status'] == 'Enviado') echo 'selected="true"'?>>Enviado</option>
                                    <option value="Entregado" <?php if($data[0]['status'] == 'Entregado') echo 'selected="true"'?>>Entregado</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <strong>Código de seguimiento:</strong>
                                <input class="form-control mt-2" name="shipmentCode" <?php if($data[0]['details']) echo 'value="'.$data[0]['details'].'"'; ?> placeholder="Introduzca el código de seguimiento.">
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-2">
                                <strong>Precio total:</strong>
                                <p>{{$data[0]['price']}} €</p>
                            </div>
                        </div>
                        <div class="col-12 mb-5">
                            <?php

                            if($data[2]){
                                    echo '<div class="row">
                                        <div class="col-12 mb-5 bg-slateGrey-color rounded px-lg-5 px-4 py-4">
                                        <h4 class="text-center mb-3"><strong>Solicitud de devolución</strong></h4>
                                        <div class="mb-2">
                                        <strong>Razón:</strong>
                                        <p>'.$data[2]['reason'].'</p>
                                        </div>
                                        <div class="mb-2">
                                        <strong>Estado de la solicitud:</strong>
                                        <select class="form-control mt-2 w-fit" name="ticketStatus" '; if($data[2]['status'] == 'Aceptada') echo 'disabled'; echo'>
                                        <option value="Pendiente de revisión"'; if($data[2]['status'] == 'Pendiente de revisión') echo 'selected="true"'; echo'>Pendiente de revisión</option>
                                        <option value="Denegada"'; if($data[2]['status'] == 'Denegada') echo 'selected="true"'; echo'>Denegada</option>
                                        <option value="Aceptada"'; if($data[2]['status'] == 'Aceptada') echo 'selected="true"'; echo'>Aceptada</option>
                                        </select>
                                        </div>
                                        <div class="mb-3">
                                        <strong>Respuesta:</strong>
                                        <textarea rows="4" class="form-control mt-2" name="response">'; if($data[2]['statusMessage']) echo $data[2]['statusMessage']; echo'</textarea>
                                        </div>
                                        </div>
                                        </div>';
                            }

                            ?>

                            <button type="submit" class="btn btn-danger centerHorizontal"><strong>Enviar</strong></button>
                        </div>
                        <div class="col-lg-6 col-12">
                            <h4 class="text-center mb-3"><strong>Dirección de Envio</strong></h4>
                            <div class="mb-2">
                                <strong>Nombre:</strong>
                                <p>{{$data[0]['shipmentName']}}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Apellidos:</strong>
                                <p>{{$data[0]['shipmentSurnames']}}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Dirección:</strong>
                                <p>{{$data[0]['shipmentAddress']}}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Código Postal:</strong>
                                <p>{{$data[0]['shipmentPostCode']}}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <h4 class="text-center mb-3"><strong>Dirección de Facturación</strong></h4>
                            <div class="mb-2">
                                <strong>Nombre:</strong>
                                <p>{{$data[0]['billingName']}}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Apellidos:</strong>
                                <p>{{$data[0]['billingSurnames']}}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Dirección:</strong>
                                <p>{{$data[0]['billingAddress']}}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Código Postal:</strong>
                                <p>{{$data[0]['billingPostCode']}}</p>
                            </div>
                        </div>
                        <div class="col-12 mb-5">
                            <h2 class="text-center mb-3 my-5"><strong>Productos</strong></h2>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4">
                                            <strong>Producto</strong>
                                        </div>
                                        <div class="col-2">
                                            <strong>Formato</strong>
                                        </div>
                                        <div class="col-2">
                                            <strong>Cantidad</strong>
                                        </div>
                                        <div class="col-2">
                                            <strong>Precio</strong>
                                        </div>
                                        <div class="col-2">
                                            <strong>Cupon aplicado</strong>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                foreach ($data[1] as $sale){

                                    if($sale['couponUsed'] == 'FALSE') $sale['couponUsed'] = "No";

                                    if($sale['option'] == 0) $sale['option'] = 'Físico';
                                    else $sale['option'] = 'Digital';

                                    echo '<div class="col-12 mt-2">
                                        <div class="row">
                                        <div class="col-4"><p class="text-overflow-ellipsis">'.$sale['product'].'</p></div>
                                        <div class="col-2">'.$sale['option'].'</div>
                                        <div class="col-2">'.$sale['amount'].'</div>
                                        <div class="col-2">'.$sale['price'].' €</div>
                                        <div class="col-2">'.$sale['couponUsed'].'</div>
                                        </div>
                                        </div>';
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="pr-5 form-group">
                        <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('administrateShipments')}}">Volver</a></u></strong>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

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

<!-- E N D   A D M I N I S T R A T E   S H I P M E N T -->

@stop
@section('footer')
