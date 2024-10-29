@php
    use Illuminate\Support\Facades\Crypt;
@endphp
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
        
        <!-- <img src="{{ url('/') }}/public/img/email_brand.png"> -->
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/email_brand.png'))) }}">

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
      <td align="left" valign="middle" style="height:20px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="top" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">
        <table cellpadding="0" cellspacing="0" style="width:100%;padding: 0 0px; margin:0 auto; border:1px solid #D9D9D9; border-bottom: none;box-shadow: 0px 0px 14px 2px #0000000D;background: #fff;" id="remove-border">
        
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Patient Name</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Doctor Name</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ 'Dr. ' }}{{ $doctor->first_name }} {{ $doctor->last_name }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Date and Time</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ \Carbon\Carbon::parse($extAppData[0]->start)->Format('j F Y') }} {{ \Carbon\Carbon::parse($extAppData[0]->start)->Format('G:i') }} {{'to'}} {{ \Carbon\Carbon::parse($extAppData[0]->end)->Format('G:i') }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Category</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ $extAppData[0]->category }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Intrest</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ $extAppData[0]->interests }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Symptoms</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ $extAppData[0]->symptoms }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Appointment Type</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ $extAppData[0]->appointmentType }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Contact Number</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ $doctor->phone_number }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">
              Prescription</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>
            @if(!empty($extAppData[0]->notes))  
            {{ Crypt::decrypt($extAppData[0]->notes) }}
            @endif
          </strong></td>
          </tr>
          <!-- <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Meeting Details</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><a href="#" style="color:#0070D7;font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; ">{{ $extAppData[0]->phone_meeting_link }} </a></td>
          </tr> -->
        </table>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>