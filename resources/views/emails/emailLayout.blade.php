@section('header')
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Editorial Paralelo28</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body bgcolor="#D9E4E8" style="margin: 0; padding: 0;">
        <table bgcolor="#FFF" align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" style="padding: 40px 0 30px 0;">
                    <img width="200" src="{{asset('images/paraleloLogo.png')}}" alt="Logo de EditorialParalelo28"  style="display: block; width: 200px;" />
                </td>
            </tr>
                @show
                @yield('content')
                @section('footer')
            <tr>
                <td bgcolor="#FF0000" style="padding: 30px 30px 30px 30px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="75%" style="color: #FFFFFF; font-family: Arial, sans-serif; font-size: 14px;">
                                <b>&reg; EditorialParalelo28</b><br/>
                               Sistema de correo automatizado.
                            </td>
                            <td align="right">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <a href="https://www.instagram.com/editorialparalelo28">
                                                <img src="{{asset('images/instagramIco.png')}}" alt="Instagram" width="25" height="25" style="display: block;" border="0" />
                                            </a>
                                        </td>
                                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                                        <td>
                                            <a href="https://es-es.facebook.com/editorialparalelo28">
                                                <img src="{{asset('images/facebookIco.png')}}" alt="Facebook" width="25" height="25" style="display: block;" border="0" />
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
@show
