@extends('layout')
@section('header')
@section('content')

<!-- P A S S W O R D   R E S E T -->

<div class="container-fluid mt-5">
    <h3 class="text-center mb-5"><strong class="ed-text" data-content="head"><?php echo $data['user'] ?></strong> reinicie su contraseña</h3>
    <div class="row">
        <div class="col-xl-4 offset-xl-4 col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-12 offset-0">
            <form id="passwordResetForm" method="post" action="{{url('finalizePasswordReset')}}">
                {{csrf_field()}}
                <input type="hidden" name="token" value="<?php echo $data['token'] ?>">
                <div class="form-group">
                    <label for="resetPassword">Nueva contraseña</label>
                    <input type="password" class="form-control" id="resetPassword" aria-describedby="resetPassword" placeholder="Introduce tu contraseña" name="password">
                    <small class="text-danger formError">&nbsp;</small>
                </div>
                <div class="form-group">
                    <label for="resetPasswordConfirm">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="resetPasswordConfirm" aria-describedby="resetPasswordConfirm" placeholder="Repite tu contraseña" name="passwordRepeat">
                    <small class="text-danger formError">&nbsp;</small>
                </div>
                <button type="submit" class="btn btn-danger mt-4 centerHorizontal"><strong>Aceptar</strong></button>
            </form>
        </div>
    </div>
</div>

<!-- E N D   P A S S W O R D   R E S E T  -->

<script type="text/javascript" src="{{asset('js/passwordReset.min.js')}}"></script>

@stop
@section('footer')
