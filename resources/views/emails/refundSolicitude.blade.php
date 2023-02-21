@extends('emails.emailLayout')
@section('header')
@section('content')
    <tr>
        <td style="padding: 40px 30px 40px 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="color: #707070; font-family: Arial, sans-serif; font-size: 18px;">
                        <b>{{$name}} ha solicitado la devolución del pedido número {{$shipmentCode}} el día {{date('d/m/Y')}}.</b>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <b>
                            Su razón para solicitar una devolución es la siguiente:<br/><br/>
                            {{$reason}}<br/><br/>
                            Puedes administrar esta solicitud de devolución desde el siguiente <a href="{{url('administrateShipment/'.$shipmentCode)}}">enlace.</a>
                        </b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@stop
@section('footer')
