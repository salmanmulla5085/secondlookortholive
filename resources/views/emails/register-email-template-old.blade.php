<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>:: SecondLookOrtho ::</title>
        <style type="text/css">
            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }
            html,
            body {
                min-height: 100%;
                overflow-x: hidden !important;
            }
            body {
                padding: 0px 0 0 0;
                margin: 0;
                font-family: "Georgia", serif, sans-serif;
                font-size: 18px;
                font-weight: normal;
                background: #fff;
                overflow: hidden !important;
            }
            img {
                max-width: 100%;
            }
            a,
            a:hover,
            a.active,
            a:active,
            a:focus {
                outline: none;
                text-decoration: none;
            }
            a,
            a:hover,
            a:focus,
            a:active,
            a.active {
                outline: 0;
                -webkit-transition: all 0.5s ease-in-out;
                -moz-transition: all 0.5s ease-in-out;
                -ms-transition: all 0.5s ease-in-out;
                -o-transition: all 0.5s ease-in-out;
                transition: all 0.5s ease-in-out;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </head>
    <body class="">
        <table cellpadding="0" cellspacing="0" style="max-width: 960px; width: 100%; padding: 0 0px; margin: 0 auto; border: none;">
            <thead>
                <tr>
                    <th align="left" valign="middle" style="padding: 50px 25px; margin: 0; font-family: 'Georgia', serif, sans-serif; background: #ddf4f1;">
                        <img src="http://174.141.231.46/~secondlookortho/public/img/email_brand.png" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="left" valign="middle" style="height: 30px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="text-align: center; padding: 0px 25px;"><img src="{{ url('/') }}/public/img/email_img01.jpg" /></td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="text-align: center; padding: 0px 25px;">
                        <h1 style="color: #ff7522; font-size: 28px!important; font-weight: 700; font-family: 'Georgia', serif, sans-serif!important;">Hello, {{ $patient->first_name }}!</h1>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="height: 50px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="color: #000000; font-size: 18px; font-weight: 500; font-family: 'Georgia', serif, sans-serif; padding: 0px 25px;">
                      <strong style="color: #000; font-size: 18px!important; font-weight: 700; font-family: 'Georgia', serif, sans-serif!important;">Thank you for registering with us.</strong>,
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="height: 15px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="color: #000000; font-size: 18px!important; font-weight: 500; font-family: 'Georgia', serif, sans-serif!important; padding: 0px 25px;">Thanks,</td>
                </tr>
                <tr>
                    <td align="left" valign="middle" style="color: #000000; font-size: 18px!important; font-weight: 500; font-family: 'Georgia', serif, sans-serif!important; padding: 0px 25px;">Team SecondLookOrtho</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
