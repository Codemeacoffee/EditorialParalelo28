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
                            Has solicitado el restablecimiento de tu contraseña.<br/>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <b>
                            Accede al siguiente link para restablecer tu contraseña.<br/>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                        <a href="{{url('resetPassword')}}/{{$passwordResetUrl}}" style="background-color: #FF0000; color: #FFFFFF; padding: 8px 15px 8px 15px; font-size: 24px; text-decoration: none !important;">Restablecer Contraseña</a>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <b>
                            En el caso de que no hubieras pedido restablecer tu contraseña, escríbenos por favor a través del siguiente <a href="{{url('contact')}}#clientSupport">enlace</a><br/>
                            <br/>
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
