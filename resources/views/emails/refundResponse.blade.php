@extends('emails.emailLayout')
@section('header')
@section('content')
    <tr>
        <td style="padding: 40px 30px 40px 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="color: #707070; font-family: Arial, sans-serif; font-size: 18px;">
                        <b>{{$name}}, tu solicitud de devolución del pedido número {{$shipmentCode}} ha sido clasificada como {{$ticketStatus}}.</b>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        <b>
                        <?php

                        if(strlen($ticket['statusMessage']) > 0) {
                            echo 'El administrador que ha revisado su petición ha dicho al respecto:<br/><br/>'.$ticket['statusMessage'].'<br/><br/>';
                        }

                        ?>
                        Puedes ver esta solicitud de devolución desde el siguiente <a href="{{url('shipment/'.$shipmentCode)}}">enlace.</a>
                        </b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@stop
@section('footer')
