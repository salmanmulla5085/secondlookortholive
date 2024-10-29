<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>:: SecondLookOrtho ::</title>
    <style type="text/css">
      *{ padding: 0; margin: 0; box-sizing: border-box;}
      html, body {min-height: 100%;overflow-x: hidden !important;}
      body{padding:0px 0 0 0; margin: 0;font-family: 'Georgia',serif,sans-serif !important;font-size: 18px; font-weight: normal; background: #F5F5FA !important;overflow: hidden !important;}
      img{max-width: 100%;}
      a,a:hover,a.active,a:active,a:focus{outline: none; text-decoration: none;}
      a, a:hover, a:focus, a:active, a.active {outline: 0;-webkit-transition: all .5s ease-in-out;-moz-transition: all .5s ease-in-out;-ms-transition: all .5s ease-in-out;-o-transition: all .5s ease-in-out;transition: all .5s ease-in-out;text-decoration: none; cursor: pointer;}
@media screen and (max-width:680px) {

#main-tables tbody tr td {
  display: block;
  float: left;
  width: 100% !important;
}
#remove-border td{border-right: none!important;}
}
    </style>
  </head>
<body class="" style="background: #F5F5FA !important;">
<table  cellpadding="0" cellspacing="0" style="max-width: 960px; width:100%;padding: 0 0px 0rem 0; margin:0 auto; border:none; background: #F5F5FA !important;" id="main-tables">
  <thead>
    <tr>
      <th align="left" valign="middle" style="padding:30px 25px; margin:0;font-family: 'Georgia',serif,sans-serif;background: #F5F5FA !important ; text-align: center;">
        <img src="http://174.141.231.46/~secondlookortho/public/img/email_brand.png">
      </th>
    </tr>
    <tr>
      <th style="" align="left" valign="middle">
        <table cellpadding="0" cellspacing="0" style="max-width: 960px; width:100%;padding: 0 0px; margin:0 auto; border:none;"><tr><td style="width:50%; height: 10px;background: #F37A12;"></td><td style="width:50%; height: 10px;background: #02C4B7;"></td></tr></table>
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="left" valign="middle" style="height:30px;">&nbsp;</td>
    </tr>
   
    <tr>
        <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">Hello <strong style="color:#000; font-size:18px; font-weight:700;font-family: 'Georgia',serif,sans-serif;">Admin</strong>,</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:15px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">{{ $patient['first_name'] }} {{ $patient['last_name'] }} has been contacted you, please find below details submitted by user.</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:50px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="top" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">
        <table cellpadding="0" cellspacing="0" style="width:100%;padding: 0 0px; margin:0 auto; border:1px solid #D9D9D9; border-bottom: none;box-shadow: 0px 0px 14px 2px #0000000D;background: #fff;" id="remove-border">
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">First Name</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ $patient['first_name'] }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Email</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ $patient['email'] }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Phone Number</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ $patient['phone'] }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Message</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ $patient['message'] }}</strong></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:50px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">Thanks,</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:15px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">Team SecondLookOrtho</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:50px;">&nbsp;</td>
    </tr>
    
  </tbody>
  <tfoot>
    <tr>
      <th style="" align="left" valign="middle">
        <table cellpadding="0" cellspacing="0" style="max-width: 960px; width:100%;padding: 0 0px; margin:0 auto; border:none;"><tr><td style="width:50%; height: 10px;background: #F37A12;"></td><td style="width:50%; height: 10px;background: #02C4B7;"></td></tr></table>
      </th>
    </tr>
    <tr>
      <th align="left" valign="middle" style="padding:30px 25px; margin:0;font-family: 'Georgia',serif,sans-serif;background: #F5F5FA !important; text-align: center; color: #848484; font-size: 13px;font-weight: 400;">
        Copyright © 2024, SecondLook Ortho. All rights are reserved.
      </th>
    </tr>
  </tfoot>
</table>
</body>
</html>