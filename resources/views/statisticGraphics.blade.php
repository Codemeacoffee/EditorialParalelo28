@extends('adminLayout')
@section('header')
@section('content')

<!-- S T A T I S T I C S -->

<div class="container-fluid mt-5">
    <h1 class="text-center mb-5"><strong><?php echo $data[0] ?></strong></h1>

    <div class="row mt-6">

        <!-- G R A P H I C   C H A R T S -->

        <div class="col-lg-10 offset-lg-1 col-12 offset-0">
            <div class="col-xl-3 offset-xl-9 col-lg-6 offset-lg-6 col-md-8 offset-md-4 col-12 offset-0 mb-5 d-flex">
                <label class="noWrap mr-3 mt-1" for="monthFilterSelect">
                    Fecha:
                </label>
                <select id="monthFilterSelect" class="browser-default custom-select interactive"></select>
            </div>
            <div class="row">
               <div class="col-xl-6 col-lg-6 col-12">
                   <div class="mb-5 pr-2">
                       <h4 class="mb-5">Ventas en digital</h4>
                       <canvas id="bookDigitalSales"></canvas>
                   </div>
               </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="mb-5 pr-2">
                        <h4 class="mb-5">Ventas en físico</h4>
                        <canvas id="bookPhysicalSales"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="mb-5 pr-2">
                        <h4 class="mb-5">Veces añadido a lista de deseos</h4>
                        <canvas id="bookAddedToWishList"></canvas>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="mb-5 pr-2">
                        <h4 class="mb-5">Veces añadido al carrito</h4>
                        <canvas id="bookAddedToCart"></canvas>
                    </div>
                </div>
            </div>
            <div class="pr-5 pb-5 form-group">
                <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('controlPanel')}}">Volver</a></u></strong>
            </div>
        </div>
    </div>
</div>

<!-- E N D   G R A P H I C   C H A R T S -->

<script id="chartsData" type="text/javascript">

    //-- S T A R T U P   S C R I P T --//
    let months = {};
    let filter = $('#monthFilterSelect');

    let bookDigitalSales = $('#bookDigitalSales');
    let bookPhysicalSales = $('#bookPhysicalSales');
    let bookAddedToWishList = $('#bookAddedToWishList');
    let bookAddedToCart = $('#bookAddedToCart');

    <?php

        foreach ($data[1] as $key => $value){
            echo 'months[("'.$key.'")] = [];';
            foreach ($value as $current){
                echo 'months[("'.$key.'")].push(['.$current[0].', '.$current[1].', '.$current[2].', '.$current[3].']);';
            }
        }

    ?>

    $(window).on('load',function(){
        $.each(months, function(index){
            let split = index.split('-');
            let month = parseMonth(split[1]);
            let parsedDate = month+" de "+split[0];

            filter.append('<option value="'+index+'">'+parsedDate+'</option>');
        });

        let last = $('#monthFilterSelect option:last');

        last.attr('selected', true);

        let lastMonthLength = Object.keys(months[last.val()]).length;

        let dayArray = numberArray(lastMonthLength);

        let orderedDigitalSales = [];
        let orderedPhysicalSales = [];
        let orderedAddedToWishList = [];
        let orderedAddedToCart = [];

        $(months[last.val()]).each(function (index, value) {
            orderedDigitalSales.push(value[0]);
            orderedPhysicalSales.push(value[1]);
            orderedAddedToWishList.push(value[2]);
            orderedAddedToCart.push(value[3]);
        });

        let splittedDate = last.val().split('-');

        let formatedDate = '/'+splittedDate[1]+'/'+splittedDate[0];

        let digitalSalesChart = createStatisticChart(bookDigitalSales, 'line', 'Ventas en digital', dayArray, orderedDigitalSales, formatedDate);
        let physicalSalesChart = createStatisticChart(bookPhysicalSales, 'line', 'Ventas en físico', dayArray, orderedPhysicalSales, formatedDate);
        let addedToWishListChart = createStatisticChart(bookAddedToWishList, 'line', 'Veces añadido a lista de deseos', dayArray, orderedAddedToWishList, formatedDate);
        let addedToCartChart = createStatisticChart(bookAddedToCart, 'line', 'Veces añadido al carrito', dayArray, orderedAddedToCart, formatedDate);

        filter.on('change', function () {
            let targetMonth = $(this).val();

            orderedDigitalSales = [];
            orderedPhysicalSales = [];
            orderedAddedToWishList = [];
            orderedAddedToCart = [];

            $(months[targetMonth]).each(function (index, value) {
                orderedDigitalSales.push(value[0]);
                orderedPhysicalSales.push(value[1]);
                orderedAddedToWishList.push(value[2]);
                orderedAddedToCart.push(value[3]);
            });

            digitalSalesChart.data.datasets[0].data = orderedDigitalSales;
            physicalSalesChart.data.datasets[0].data = orderedPhysicalSales;
            addedToWishListChart.data.datasets[0].data = orderedAddedToWishList;
            addedToCartChart.data.datasets[0].data = orderedAddedToCart;

            let targetMonthDays = numberArray(Object.keys(months[targetMonth]).length);

            digitalSalesChart.data.labels = targetMonthDays;
            physicalSalesChart.data.labels = targetMonthDays;
            addedToWishListChart.data.labels = targetMonthDays;
            addedToCartChart.data.labels = targetMonthDays;

            let splittedDate = targetMonth.split('-');


            let formatedDate = '/'+splittedDate[1]+'/'+splittedDate[0];

            digitalSalesChart.options.tooltips.callbacks.title = function(tooltipItem) {return 'Fecha: '+tooltipItem[0].xLabel + formatedDate;};
            physicalSalesChart.options.tooltips.callbacks.title = function(tooltipItem) {return 'Fecha: '+tooltipItem[0].xLabel + formatedDate;};
            addedToWishListChart.options.tooltips.callbacks.title = function(tooltipItem) {return 'Fecha: '+tooltipItem[0].xLabel + formatedDate;};
            addedToCartChart.options.tooltips.callbacks.title = function(tooltipItem) {return 'Fecha: '+tooltipItem[0].xLabel + formatedDate;};

            digitalSalesChart.update();
            physicalSalesChart.update();
            addedToWishListChart.update();
            addedToCartChart.update();
        });

        $('#chartsData').remove();
    });

    //-- E N D   S T A R T U P   S C R I P T --//

</script>

<!-- E N D   S T A T I S T I C S -->

@stop
@section('footer')
