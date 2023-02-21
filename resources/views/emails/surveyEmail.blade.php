@extends('emails.emailLayout')
@section('header')
@section('content')
    <tr>
        <td style="padding: 40px 30px 40px 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="color: #707070; font-family: Arial, sans-serif; font-size: 24px;">
                        <b>Estimado {{$name}}</b>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <p><b>Desde Editorial Paralelo 28 queremos saber tu opinión sobre tu compra de los siguientes artículos:</b></p>
                        <?php

                            foreach ($products as $product){
                                echo '<p><b>• '.$product.'</b></p>';
                            }

                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <b>
                            Si dispones de un minuto, accede al siguiente enlace para realizar una encuesta.
                            Recuerda que este enlace solo es válido durante 1 semana a partir del momento en que reciba este correo.
                        </b>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                        <a href="{{url('personalSurvey')}}/{{$surveyUrl}}" style="background-color: #FF0000; color: #FFFFFF; padding: 8px 15px 8px 15px; font-size: 24px;text-decoration: none !important;">Realizar Encuesta</a>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <b>
                            De nuevo, muchas gracias por confiar en nosotros.<br/>
                            <br/>

                            Un saludo,
                            Equipo Editorial Paralelo 28.
                        </b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@stop
@section('footer')
