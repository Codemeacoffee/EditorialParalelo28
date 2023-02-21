@extends('adminLayout')
@section('header')
@section('content')

<!-- A D M I N   T A X E S -->

<div class="container-fluid mt-5">
    <h1 class="text-center"><strong>Impuestos</strong></h1>

    <div class="row mt-6 position-relative">
        <div class="col-lg-10 offset-lg-1 col-12 offset-0">
            <form method="POST" action="{{url('updateTaxes')}}">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label for="igicPhysical"><strong class="grey-color">IGIC para productos en físico (%)*</strong></label>
                            <input id="igicPhysical" type="number" step="0.01" class="form-control" name="igicPhysical" value="<?php echo $data[0] ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label for="igicDigital"><strong class="grey-color">IGIC para productos en digital (%)*</strong></label>
                            <input id="igicDigital" type="number" step="0.01" class="form-control" name="igicDigital" value="<?php echo $data[1] ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label for="ivaPhysical"><strong class="grey-color">IVA para productos en físico (%)*</strong></label>
                            <input id="ivaPhysical" type="number" step="0.01" class="form-control" name="ivaPhysical" value="<?php echo $data[2] ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label for="ivaDigital"><strong class="grey-color">IVA para productos en digital (%)*</strong></label>
                            <input id="ivaDigital" type="number" step="0.01" class="form-control" name="ivaDigital" value="<?php echo $data[3] ?>" required>
                        </div>
                    </div>
                </div>
                <div class="grid-2-rows mobile2rows mt-5">
                    <div class="pr-5 form-group mt-2">
                        <strong><u><a class="red-color interactive backToAccountConfig" href="{{url('controlPanel')}}">Volver</a></u></strong>
                    </div>
                    <div class="pr-5 form-group text-right">
                        <button type="submit" class="btn btn-danger"><strong>Guardar</strong></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- E N D   A D M I N   T A X E S -->

@stop
@section('footer')
