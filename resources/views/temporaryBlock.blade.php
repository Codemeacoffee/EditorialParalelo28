@extends('layout')
@section('header')
@section('content')

<!-- T E M P O R A R Y   B L O C K -->

<div class="container-fluid mt-5">
    <h1 class="text-center mb-5"><strong>Has sido bloqueado temporalmente</strong></h1>
    <div class="row">
        <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-12 offset-0">
            <div>
                <img alt="Icono indicando el bloqueo temporal" class="w-50 centerHorizontal" src="{{asset('images/blocked.png')}}">
                <p class="text-center">Debido a sus reiterados intentos de inicio de sesión fallidos, ha sido bloqueado temporalmente.<br>
                    Puede ver el tiempo restante de bloqueo a continuación:</p>
                <h1 class="fade text-center red-color mt-5 noWrap" id="remainingBlockedTime">&nbsp</h1>
            </div>
        </div>
    </div>
</div>

<!-- E N D   T E M P O R A R Y   B L O C K -->

<script id="temporaryBlockScript" type="text/javascript">
    $(window).on('load', function () {
        let countDownArea = $('#remainingBlockedTime');

        let date = new Date();
        date.setSeconds(date.getSeconds() <?php echo'+ '.$data ?>);

        countDown(date, countDownArea, 'm', '<a class="smallText" href="{{url("frontalOpenLogin")}}">Ya puede volver a iniciar sesión.</a>');

        countDownArea.addClass('show');
        $('#temporaryBlockScript').remove();
    });
</script>

@stop
@section('footer')
