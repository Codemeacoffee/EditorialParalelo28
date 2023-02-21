@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N I S T R A T E   S H I P M E N T S -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Pedidos</strong></h1>

    <div class="row mt-6 position-relative">
        <div class="col-xl-6 col-12 mb-lg-0 mb-6">
            <h4 class="text-center mb-4"><strong>Pedidos en curso</strong></h4>
            <div class="w-100 overflow-auto">
                <table class="table text-da">
                    <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fecha de pago</th>
                        <th scope="col">Detalles</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($data[0] as $shipment){
                        echo '<tr " '; if($shipment['ticket']) echo'title="Pedido con petición de devolución sin responder" class="text-danger"'; echo'>
                            <th scope="col" class="text-center">'.$shipment['shipmentCode'].'</th>
                            <th scope="col" class="text-center">'.$shipment['user'].'</th>
                            <th scope="col" class="text-center">'.$shipment['email'].'</th>
                            <th scope="col" class="text-center">'.$shipment['created_at'].'</th>
                            <th scope="col" class="text-center"><a href="'.url('administrateShipment/'.$shipment['shipmentCode']).'" target="_blank">Ver más</a></th>
                            </tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <h4 class="text-center mb-4"><strong>Pedidos finalizados</strong></h4>
            <div class="w-100 overflow-auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fecha de pago</th>
                        <th scope="col">Detalles</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($data[1] as $shipment){
                        echo '<tr " '; if($shipment['ticket']) echo'title="Pedido con petición de devolución sin responder" class="text-danger"'; echo'>
                            <th scope="col" class="text-center">'.$shipment['shipmentCode'].'</th>
                            <th scope="col" class="text-center">'.$shipment['user'].'</th>
                            <th scope="col" class="text-center">'.$shipment['email'].'</th>
                            <th scope="col" class="text-center">'.$shipment['created_at'].'</th>
                            <th scope="col" class="text-center"><a href="'.url('administrateShipment/'.$shipment['shipmentCode']).'" target="_blank">Ver más</a></th>
                            </tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="pr-5 form-group">
        <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('controlPanel')}}">Volver</a></u></strong>
    </div>
</div>

<!-- E N D   A D M I N I S T R A T E   S H I P M E N T S -->

@stop
@section('footer')
