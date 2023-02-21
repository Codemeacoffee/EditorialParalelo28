@extends('emails.emailLayout')
@section('header')
@section('content')
    <tr>
        <td style="padding: 40px 30px 40px 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0;color: #707070; font-family: Arial, sans-serif; font-size: 16px;line-height: 20px;">
                        {!! $content !!}<br/>
                    </td>
                </tr>
                <?php

                if(isset($imageLink) && $imageLink != null){
                    echo'<tr>
                        <td align="center" style="padding: 40px 0 30px 0;">
                        <img width="200px" src="'.$imageLink.'" alt="Imagen de '.$subject.'"  style="display:block;" />
                        </td>
                        </tr>';
                }

                ?>
                <tr>
                    <td align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                        <p>Recuerda que siempre puedes darte de baja de nuestro newsletter
                            haciendo click <a href="{{url('newsletterCancelSubscription')}}/{{$email}}">aquí</a>.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@stop
@section('footer')
