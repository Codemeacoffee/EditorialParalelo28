@extends('layout')
@section('header')
@section('content')

<!-- S H I P M E N T   I N F O -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Pedido número {{$data[0]['shipmentCode']}}</strong></h1>

    <!-- R E F O U N D   M O D A L -->
    <div class="modal fade" id="modal-refund" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="{{url('refund')}}">
                    {{csrf_field()}}
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center">Solicitar devolución</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-5">
                        <div>
                            <p>
                                Estas a punto de solicitar una devolución, recuerda que para que esta sea
                                válida debe cumplir con los puntos especificados en nuestra
                                <a href="{{url('shipmentsAndRefundsPolicy')}}" target="_blank">Política de envíos y devoluciones.</a>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="reasons"><strong class="grey-color">Motivo*</strong></label>
                            <textarea id="reasons" rows="4" class="form-control" name="reasons" placeholder="Explícanos brevemente las razones por las que deseas una devolución" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger"><strong>Enviar</strong></button>
                    </div>
                    <input type="hidden" name="shipment" value="{{$data[0]['shipmentCode']}}">
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-12">
            <div class="container-fluid">
                <div class="row mt-6 position-relative">
                    <div class="col-lg-6 col-12 mb-5">
                        <div class="mb-2">
                            <strong>Estado:</strong>
                            <p>{{$data[0]['status']}}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Código de seguimiento:</strong>
                            <?php

                            if(strlen($data[0]['details']) > 0) echo '<a href="https://www.correos.es/ss/Satellite/site/aplicacion-4000003383089-herramientas_y_apps/detalle_app-sidioma=es_ES?numero='.$data[0]['details'].'"><p class="m-0">'.$data[0]['details'].'</p></a>';
                            else echo '<p>Aun no se ha aportado un código de seguimiento para este pedido.</p>';

                            if($data[0]['status'] == 'Enviado') echo '<a href="'.url('confirmArrival/'.$data[0]['shipmentCode']).'"><button class="btn btn-danger mt-2"><strong>Confirmar llegada</strong></button></a>';

                            ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="mb-2">
                            <strong>Precio total:</strong>
                            <p>{{$data[0]['price']}} €</p>
                        </div>
                        <div class="mb-2">
                            <?php

                            $digital = false;

                            foreach ($data[1] as $sale){
                                if($sale['option'] == 1){
                                    $digital = true;
                                    break;
                                }
                            }

                            $expireDate = date('Y-m-d h:i:s', strtotime($data[0]['created_at'] . '+14 days'));
                            $now = date('Y-m-d h:i:s');

                            if($data[2]) echo '<p class="mt-4 grey-color">Ya ha solicitado una devolución para este pedido.</p>';
                            else if (strtotime($now) > strtotime($expireDate)) echo '<p class="mt-4 grey-color">Han pasado más de 14 días desde la compra de este pedido, no puede solicitar una devolución.</p>';
                            else if(!$digital) echo'<button class="btn btn-danger mt-2" data-toggle="modal" data-target="#modal-refund"><strong>Solicitar devolución</strong></button>';
                            else echo '<p class="mt-4 grey-color">No se pueden solicitar devoluciones para productos en formato digital.</p>';

                            ?>

                        </div>
                    </div>
                    <?php

                    if($data[2])
                        echo '<div class="col-12 mb-5 bg-slateGrey-color rounded px-5 py-3">
                            <h4 class="text-center mb-3"><strong>Solicitud de devolución</strong></h4>
                            <div class="mb-2">
                            <strong>Razón:</strong>
                            <p>'.$data[2]['reason'].'</p>
                            </div>
                            <div class="mb-2">
                            <strong>Estado de la solicitud:</strong>
                            <p>'.$data[2]['status'].'</p>
                            </div>
                            <div class="mb-2">
                            <strong>Respuesta:</strong>
                            <p>'.$data[2]['statusMessage'].'</p>
                            </div>
                            </div>';

                    ?>
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
                    <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('home')}}">Volver</a></u></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E N D   S H I P M E N T   I N F O -->

@stop
@section('footer')
