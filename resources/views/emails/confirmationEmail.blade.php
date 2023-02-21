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
                <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                    <b>
                        Gracias por tu paciencia. Ya sólo falta un pequeño paso para ti, pero un gran paso para nosotros. <br/>
                    </b>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img width="250px" src="{{asset('images/moonStep.png')}}" alt="Primer paso del hombre sobre la luna."  style="display: block;" />
                </td>
            </tr>
            <tr>
                <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                    <b>
                        Accede al siguiente link para confirmar tu cuenta con nosotros.<br/>
                    </b>
                </td>
            </tr>
            <tr>
                <td align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                    <a href="{{url('confirmationEmail')}}/{{$confirmationEmailUrl}}" style="background-color: #FF0000; color: #FFFFFF; padding: 8px 15px 8px 15px; font-size: 24px;text-decoration: none !important;">Activar</a>
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
