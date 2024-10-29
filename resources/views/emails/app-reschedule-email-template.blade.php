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
  <?php 
    if($operation == 'app_booked'){
      $msg = "Thanks for booking an appointment on SecondLookOrtho. Here are the details of your appointment:";
    } elseif($operation == 'app_resch'){
      $msg = "Thanks for booking an appointment on SecondLookOrtho. your appointment has been reschedule, Here are the details of your appointment:";
    } elseif($operation == 'doctor_confirm'){
      $msg = "Thanks for booking an appointment on SecondLookOrtho. your appointment with Dr. ". $doctor->first_name . " " . $doctor->last_name . " has been confirmed by doctor. Here are the details of the confirmed appointment:";
    } elseif($operation == 'doctor_reject'){
      $msg = "Your appointment with Dr. ". $doctor->first_name . " " . $doctor->last_name . " has been rejected by doctor. Here are the details of the rejected appointment:";
    } elseif($operation == 'doctor_cancel'){
      $msg = "Your appointment with Dr. ". $doctor->first_name . " " . $doctor->last_name . " has been cancelled by doctor. Here are the details of the cancelled appointment:";
    } else {
      $msg = "";
    }
  ?>
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
        @if($rec_by == 'patient')
            <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">Dear <strong style="color:#000; font-size:18px; font-weight:700;font-family: 'Georgia',serif,sans-serif;">{{ $patient->first_name }} {{ $patient->last_name }}</strong>,</td>
        @elseif($rec_by == 'doctor')
            <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">Dear <strong style="color:#000; font-size:18px; font-weight:700;font-family: 'Georgia',serif,sans-serif;">{{ 'Dr. ' }} {{ $doctor->first_name }} {{ $doctor->last_name }}</strong>,</td>
        @endif
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:15px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;"><?php if($operation == 'app_resch' && $rec_by == 'patient'){ echo 'Thanks for booking an appointment on SecondLookOrtho. Your appointment has been reschedule, Here are the details of your appointment:'; } elseif($operation == 'app_resch' && $rec_by == 'doctor'){ echo $patient->first_name.' '.$patient->last_name. ' has been reschedule appointment, Here are the details of your appointment:';} ?></td>
    </tr>
    <tr>
      <td align="left" valign="middle" style="height:50px;">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="top" style="color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;padding:0px 50px;">
        <table cellpadding="0" cellspacing="0" style="width:100%;padding: 0 0px; margin:0 auto; border:1px solid #D9D9D9; border-bottom: none;box-shadow: 0px 0px 14px 2px #0000000D;background: #fff;" id="remove-border">
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Patient Name</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Doctor Name</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid  #D9D9D9; "><strong>{{ 'Dr. ' }}{{ $doctor->first_name }} {{ $doctor->last_name }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Date and Time</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ \Carbon\Carbon::parse($app_details->start)->Format('j F Y') }} {{ \Carbon\Carbon::parse($app_details->start)->Format('G:i') }} {{'to'}} {{ \Carbon\Carbon::parse($app_details->end)->Format('G:i') }}</strong></td>
          </tr>
          <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Appointment Type</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><strong>{{ $app_details->appointmentType }}</strong></td>
          </tr>
          <!-- <tr>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif;width: 33.333%;border-right: 1px solid #D9D9D9;border-bottom: 1px solid #D9D9D9;">Meeting Details</td>
            <td style="padding:10px 15px;color:#000000; font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; width:66.667%;border-bottom: 1px solid #D9D9D9; "><a href="#" style="color:#0070D7;font-size:18px; font-weight:500;font-family: 'Georgia',serif,sans-serif; ">{{ $app_details->phone_meeting_link }} </a></td>
          </tr> -->
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