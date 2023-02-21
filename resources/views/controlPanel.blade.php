@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N   C O N T R O L   P A N E L -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Bienvenido </strong><?php echo $userData['name']; ?></h1>

    <div class="row mt-6 position-relative">

        <!-- A D M I N   O P T I O N   S I D E B A R -->

        <div id="controlPanelOptions" class="col-lg-3 col-12">
            <div class="stickyBox">
                <p id="selected"><strong class="interactive">Resumen</strong></p>
                <p><strong class="grey-color interactive hoverRed">Estadísticas</strong></p>
                <p><strong class="grey-color interactive hoverRed"><a class=" text-decoration-none grey-color hoverRed" href="{{url('newsletterPromotion')}}">Newsletter</a></strong></p>
                <p><strong class="grey-color interactive hoverRed"><a class=" text-decoration-none grey-color hoverRed" href="{{url('expediteCoupons')}}">Cupones</a></strong></p>
                <p><strong class="grey-color interactive hoverRed"><a class=" text-decoration-none grey-color hoverRed" href="{{url('administrateShipments')}}">Pedidos</a></strong></p>
                 <p><strong class="grey-color interactive hoverRed"><a class=" text-decoration-none grey-color hoverRed" href="{{url('administrateTaxes')}}">Impuestos</a></strong></p>
                <p><strong class="grey-color interactive hoverRed"><a class=" text-decoration-none grey-color hoverRed" href="{{url('home')}}">Mi cuenta de usuario</a></strong></p>
                <p><strong><u><a href="{{url('closeSession')}}" class="red-color hoverRed">Cerrar sesión</a></u></strong></p>
            </div>
        </div>
        <div id="controlPanelOptionsMobile" class="d-none col-lg-3 col-12">
            <select class="form-control">
                <option value="0">Resumen</option>
                <option value="1">Estadísticas</option>
                <option value="{{url('newsletterPromotion')}}">Newsletter</option>
                <option value="{{url('expediteCoupons')}}">Cupones</option>
                <option value="{{url('administrateShipments')}}">Pedidos</option>
                <option value="{{url('administrateTaxes')}}">Impuestos</option>
                <option value="{{url('home')}}">Mi cuenta de usuario</option>
                <option value="{{url('closeSession')}}">Cerrar sesión</option>
            </select>
        </div>

        <!-- E N D   A D M I N   O P T I O N   S I D E B A R -->

        <div class="col-lg-9 col-12 p-0">

            <!-- S U M M A R Y -->

            <div id="summary">
                <div class="row px-4">
                    <div class="col-lg-10 col-12 pl-lg-5 pl-0">
                        <div class="table-responsive overflow-auto">
                            <table summary="Estadísticas de los libros" class="table table-bordered table-hover border">
                                <tbody>

                                <tr class="bg-slateGrey-color">
                                    <td class="text-left"><strong>Últimos cambios</strong></td>
                                    <td><strong>Fecha</strong></td>
                                    <td><strong>Hora</strong></td>
                                </tr>

                                <?php

                                foreach ($data[2] as $latestChange){
                                    echo'<tr>
                                    <td class="text-left">'.$latestChange[0].'</td>
                                    <td>'.$latestChange[1].'</td>
                                    <td>'.$latestChange[2].'</td>
                                    </tr>';
                                }

                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <div class="row w-100">
                    <div class="col-lg-10 col-12 pr-0">
                        <div class="pl-lg-5 pl-0 mt-5">
                            <h4 class="mb-5">Ventas Generales</h4>
                            <canvas id="salesCanvas"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row w-100">
                    <div class="col-lg-10 col-12 pr-0">
                        <div class="pl-lg-5 pl-0 mt-5">
                            <h4 class="mb-5">Visitantes</h4>
                            <canvas id="visitorCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- E N D   S U M M A R Y -->

            <!-- S T A T I S T I C S -->

            <div id="statistics" class="displayNone">
                <div class="adminStatisticsFilter">
                    <div class="innerStatisticsFilter">
                        <div class="col-lg-4 offset-lg-8 col-12 offset-0 d-flex">
                            <label class="noWrap mr-3 mt-1" for="adminStatisticsFilter">
                                Buscar
                            </label>
                            <input id="adminStatisticsFilter" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 col-xs-12">
                        <div class="table-responsive overflow-auto">
                            <table summary="Estadísticas de los libros" class="table table-bordered table-hover">
                                <tbody>

                                <?php

                                foreach ($data[1] as $book){
                                    echo'<tr>
                                    <td class="text-left">'.$book['title'].'</td>
                                    <td>'.$book['category'].'</td>
                                    <td>'.$book['totalSales'].' uds. vendidas</td>
                                    <td><a class="text-decoration-none white-color" href="'.url('statistics/'.$book['title']).'"><button class="btn btn-danger">Ver detalles</button></a></td>
                                    </tr>';
                                }

                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- E N D  S T A T I S T I C S -->

        </div>
    </div>
</div>

<script id="chartData" type="text/javascript">

    //-- S T A R T U P   S C R I P T --//

    $(window).on('load', function () {
        let daysInMonth = new Date(<?php echo date('Y').', '.date('m').', 0'; ?>).getDate();
        let dayArray = numberArray(daysInMonth);

        let visitorCanvas = $('#visitorCanvas');
        let visitorData = [];

        let salesCanvas = $('#salesCanvas');
        let salesData = [];

        <?php

            foreach ($data[0][1] as $visitors) echo 'visitorData.push('.$visitors.');';

        ?>

        <?php

            foreach ($data[0][0] as $sales) echo 'salesData.push('.$sales.');';

        ?>

        createStatisticChart(visitorCanvas, 'line', 'Visitantes', dayArray, visitorData, <?php echo '"/'.date('m').'/'.date('Y').'"'; ?>);
        createStatisticChart(salesCanvas, 'line', 'Ventas', dayArray, salesData, <?php echo '"/'.date('m').'/'.date('Y').'"'; ?>);

        $('#chartData').remove();

    });

    //-- E N D   S T A R T U P   S C R I P T --//

</script>

<!-- E N D   A D M I N   C O N T R O L   P A N E L -->

@stop
@section('footer')
