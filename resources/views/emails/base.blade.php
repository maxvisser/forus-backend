<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body style="{{ implementation_config('base.body_style') }}" bgcolor="{{ implementation_config('base.body_bg_color') }}">
    <center>
        <table id="wrapperTable" cellpadding="0" cellspacing="0" border="0" style="{{ implementation_config('base.wrapper_table') }}" bgcolor="{{ implementation_config('base.body_bg_color') }}">
            <tr>
                <td valign="top" align="center" style="border-collapse: collapse;">
                    <div id="wrapper" style="{{ implementation_config('base.wrapper') }}">
                        <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0; width: 100% !important; text-align: center; background: #fff; margin: 0px auto;" bgcolor="#fff">
                            <tr>
                                <td style="border-collapse: collapse; padding: 24px 24px 32px;">
                                    <h1 style="{{ implementation_config('base.h1_style') }}"> @yield('title')</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-collapse: collapse; padding-bottom: 25px;">
                                    @if(trim($__env->yieldContent('header_image')))
                                        <img src="@yield('header_image')" style="width: 297px; display: block; margin: 0 auto;">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="border-collapse: collapse; padding-left: 24px; padding-right: 24px;">
                                    <p style="margin: 0; color: #2e3238; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                                        @yield('html')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="border-collapse: collapse; padding-bottom: 25px;">
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="border-collapse: collapse;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0; width: 166px!important; text-align: center; margin: 0px auto;">
                                        <tr>
                                            <td align="center" style="{{ implementation_config('base.button_td') }}">
                                                <a href="@yield('link')" target="_blank" style="{{ implementation_config('base.button') }}">@yield('button_text')</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="border-collapse: collapse; padding-bottom: 25px;">
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </center>
    </body>
</html>
