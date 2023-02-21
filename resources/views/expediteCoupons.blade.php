@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N   E X P E D I T E   C O U P O N S -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Cupones</strong></h1>

    <div class="row mt-6 position-relative">
        <div class="col-lg-4 col-12 mb-lg-0 mb-6">
            <form method="post" action="{{url('generateCoupons')}}">
                {{csrf_field()}}
                <h4 class="text-center mb-4"><strong>Generar cupones</strong></h4>
                <div class="form-group">
                    <label for="couponCode">Código</label>
                    <input type="text" maxlength="10" class="form-control" id="couponCode" aria-describedby="couponCode" name="code">
                </div>
                <div class="form-group">
                    <label for="couponDiscount">Descuento (Porcentaje)*</label>
                    <input type="number" min="1" max="100"  class="form-control" id="couponDiscount" aria-describedby="couponDiscount" name="discount" required>
                </div>
                <div class="form-group">
                    <label for="couponsAmount">Cantidad de cupones a generar*</label>
                    <input type="number" min="1" max="50" class="form-control" id="couponsAmount" aria-describedby="couponsAmount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="couponsUses">Cantidad de usos de los cupones*</label>
                    <input type="number" min="1" max="500" class="form-control" id="couponsUses" aria-describedby="couponsUses" name="uses" required>
                </div>
                <div class="form-group">
                    <label for="couponsValidUntil">Fecha de caducidad</label>
                    <input type="date" min="<?php echo date("Y-m-d"); ?>" class="form-control" id="couponsValidUntil" aria-describedby="couponsValidUntil" name="validUntil">
                </div>
                <button type="submit" class="btn btn-danger mt-3 centerHorizontal"><strong>Generar</strong></button>
            </form>
        </div>
        <div class="col-lg-4 col-12 mb-lg-0 mb-6">
            <h4 class="text-center mb-4"><strong>Cupones activos</strong></h4>
            <div class="w-100 overflow-auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Descuento</th>
                        <th scope="col">Usos restantes</th>
                        <th scope="col">Fecha de caducidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($data[0] as $activeCoupon){
                        echo '
                    <tr>
                    <td>'.$activeCoupon['code'].'</td>
                    <td>'.$activeCoupon['discount'].'%</td>
                    <td>'.$activeCoupon['uses'].'</td><td>';
                        if(!$activeCoupon['valid_until']) echo '∞';
                        else echo $activeCoupon['valid_until'];
                        echo'<a href="'.url('deleteCoupon/'.$activeCoupon['id']).'" class="float-right noUnderline"><span title="Borrar" class="bg-red-color white-color interactive rounded pb-1 px-2" aria-hidden="true">×</span></a></td></tr>';
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <h4 class="text-center mb-4"><strong>Cupones inactivos</strong></h4>
            <div class="w-100 overflow-auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Descuento</th>
                        <th scope="col">Usos restantes</th>
                        <th scope="col">Fecha de caducidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($data[1] as $inactiveCoupon){
                        echo '
                    <tr>
                    <td>'.$inactiveCoupon['code'].'</td>
                    <td>'.$inactiveCoupon['discount'].'%</td>
                    <td>'.$inactiveCoupon['uses'].'</td><td>';
                        if(!$inactiveCoupon['valid_until']) echo '∞';
                        else echo $inactiveCoupon['valid_until'];
                        echo'<a href="'.url('deleteCoupon/'.$inactiveCoupon['id']).'" class="float-right noUnderline"><span title="Borrar" class="bg-red-color white-color interactive rounded pb-1 px-2" aria-hidden="true">×</span></a></td></tr>';
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

<!-- E N D   A D M I N   E X P E D I T E   C O U P O N S -->

@stop
@section('footer')
