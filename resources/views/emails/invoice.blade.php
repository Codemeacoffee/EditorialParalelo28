@extends('emails.emailLayout')
@section('header')
@section('content')
<tr>
    <td style="padding: 40px 30px 40px 30px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" style="font-family: Arial, sans-serif; font-size: 24px;">
                    <b>Pedido número {{$shoppingHistory['shipmentCode']}}</b>
                </td>
            </tr>
            <tr>
                <td align="left" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                    <?php 
                        if($company[0] == 1){
                            echo'
                            <b><u>Detalles Empresariales: </u></b><br/><br/>
                            <p><b>Compañía:</b> '.$company[1].'</p>
                            <p><b>CIF:</b> '.$company[2].'</p>
                            <br/><br/>';
                        } 
                    ?>
                    <b><u>Detalles de Facturación: </u></b><br/><br/>
                    <p><b>Nombre:</b> {{$shoppingHistory['shipmentName']}}</p>
                    <p><b>Apellidos:</b> {{$shoppingHistory['shipmentSurnames']}}</p>
                    <p><b>Dirección:</b> {{$shoppingHistory['shipmentAddress']}}</p>
                    <p><b>Código Postal:</b> {{$shoppingHistory['shipmentPostCode']}}</p>
                    <br/>
                    <b><u>Detalles de Envío: </u></b><br/><br/>
                    <p><b>Nombre:</b> {{$shoppingHistory['billingName']}}</p>
                    <p><b>Apellidos:</b> {{$shoppingHistory['billingSurnames']}}</p>
                    <p><b>Dirección:</b> {{$shoppingHistory['billingAddress']}}</p>
                    <p><b>Código Postal:</b> {{$shoppingHistory['billingPostCode']}}</p>
                </td>
            </tr>
            <tr>
                <td align="left">
                  <table border="1" bordercolor="#707070" cellpadding="0" cellspacing="0" width="100%">
                    <tr >
                        <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;"><b>Producto</b></td>
                        <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;"><b>Formato</b></td>
                        <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;"><b>Cantidad</b></td>
                        <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;"><b>Precio</b></td>
                     </tr>
                      <?php
                        foreach($sales as $sale){
                            echo '<tr>
                            <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;">'.$sale['title'].'</td>
                            <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;">';
                            
                            if($sale['option'] == 0) echo 'Físico';
                            else echo 'Digital';
                        
                            echo'</td>
                            <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;">'.$sale['amount'].'</td>
                            <td style="padding: 5px 5px 5px 5px; font-family: Arial, sans-serif; font-size: 14px;">'.$sale['price'].'€</td>
                            </tr>';
                        }
                      ?>
                    </table>
                     <br/>
                      <p style="font-size: 16px; font-family: Arial, sans-serif;">Total: <b>{{$shoppingHistory['price']}}€</b></p>
                        <?php
                    
                            if($coupon) echo '<p>Se ha utilizado el cupón <b>'.$coupon['code'].'</b> que ha aplicado un descuento del <b>'.$coupon['discount'].'% al pedido.</b></p>'; 
                    
                        ?>
                      <br/>
                </td>
            </tr>
            <tr>
                <td align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                    <p>Adjunto a este correo puede encontrar un documento PDF con su factura.</p><br/>
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