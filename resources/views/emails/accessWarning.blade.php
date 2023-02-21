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
                            Hemos detectado actividad inusual en tu cuenta. <br/>
                            El día {{$date}} se intentó iniciar sesión en su cuenta de forma erronea repetidas veces.<br/>
                            Si no fuiste tu, ponte en contacto con nuestro  <a href="{{url('contact')}}#clientSupport">soporte técnico</a> y comunícales lo sucedido.
                        </b>
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
