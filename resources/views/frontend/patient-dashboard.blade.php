@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php
    $previousUrl = url()->previous();
    $previousPath = parse_url($previousUrl, PHP_URL_PATH);
    $LoginSegments = trim($previousPath, '/');
?>
<style>
  .patent-reviews02{background:#FFEFE5;position:relative;
    margin-left: 4px !important;
  }
/*--------------- 06-09-2024 ------------*/
.join-call {
  padding: 1rem 1rem;
  background: #02C4B7;
  font-size: 16px;
  color: #fff;
  margin-top: 1rem ;
}
.join-call a {
  color: #fff;
}
.bigger-text {
  font-size: 24px;
  font-weight: 600;
}

.imgs {
  min-width: 44px;
  max-width: 44px;
}
@media screen and (max-width:680px) {
.join-call {
    text-align: center;
  flex-direction: column;
  gap: 1rem;
}
.mobile-text {
  flex-direction: column;
}
}

</style>
<main id="main-page">

                              <div class="">
                                      @if ($errors->any())
                                      <div class="alert alert-danger">
                                          <ul>
                                              @foreach ($errors->all() as $error)
                                                  <li>{{ $error }}</li>
                                              @endforeach
                                              
                                          </ul>
                                          
                                      </div>
                                      @endif
                                  
                                      @if (session('success'))
                                          <div class="alert alert-success">
                                              {{ session('success') }}
                                          </div>
                                      @endif

                                      
                                 
                              </div>        
                             
<div class="book-bg">
  <ul>
      <li class="<?= $result['record_type'] == 'upcoming' ? 'active' : '' ?>">
        <a href="{{ url('/') }}/patient-dashboard/upcoming">Upcoming Appointments</a>
      </li>
      <li class="<?= $result['record_type'] == 'past' ? 'active' : '' ?>">
          <a href="{{ url('/') }}/patient-dashboard/past">Past Appointments</a>
      </li>
  </ul>
  
  <a href="{{ url('/') }}/book_appointment" class="btn btn-book d-flex align-items-center gap-2"><img src="{{ url('/public/frontend/img/Layer 7.png') }}"> Book  an Appointment</a>
  
</div>

<div class="accordion row m-0" id="accordionExample">
    
<?php
if(!empty($result['appointments_booked']) && count($result['appointments_booked']) > 0)
{
  $i =0;
foreach($result['appointments_booked'] as $k=>$appointment)    
{ $i++;
  $Patient_sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id = $appointment->patient_id";
  $users = DB::select($Patient_sql);
  $PatientData = collect($users);               
  
  if($PatientData && count($PatientData) > 0){
      $patient_phone_number = $PatientData[0]->phone_number;
  } else {
      $patient_phone_number = '';
  }
?>

  <div class="col-12 col-md-2 col-lg-1 p-0 d-flex align-items-stretch"><div class="pad-15 bg-white w-100 text-center d-flex flex-column gap-1"><span class="date">{{ \Carbon\Carbon::parse($appointment->start)->Format('j M') }}</span><span class="day">
  {{ \Carbon\Carbon::parse($appointment->start)->Format('D') }}
  </span></div>
  </div>
  <div class="col-12 col-md-10 col-lg-11 p-0 d-flex align-items-stretch"><div class="pad-15 pe-0 pb-0 w-100">
    <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
      <h2 class="accordion-header">
        <button class="accordion-button <?= ($i == 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
        <div class="patients-list mb-0 me-3 w-100">
            <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j M Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
            <li><span>Category </span>{{ $appointment->category }}</li>
            <li><span>Doctor</span>{{ 'Dr.' }}{{ $appointment->doctor_first_name }} {{ $appointment->doctor_last_name }}</li>
            </ul><span class="confirm">@if($appointment->appointment_status == 'In-Process' && $appointment->start < date('Y-m-d H:i:s')) {{ 'Expired' }} @else {{ $appointment->appointment_status }} @endif</span>
          </div>
        </button>
      </h2>
      <div id="collapse_{{ $appointment->id }}" class="accordion-collapse collapse <?= ($i == 1) ? 'show' : '' ?>" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          <div class="summries">
            <div class="row">
              <div class="col-12 col-md-9 col-lg-10">
                <div class="row">
                  <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                      <span>Doctor Name : </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ 'Dr.' }}{{ $appointment->doctor_first_name }} {{ $appointment->doctor_last_name }}</div></div></div></div>
                  <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                      <span>Charges :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">${{ $appointment->amount }} </div></div>
                      </div></div>
                </div>
                 <div class="row">
                  <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>Appointment Type :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                      <div class="mb-3">{{ $appointment->appointmentType }}</div></div></div></div>
                      <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                          <span>Contact Number :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $appointment->doctor_phone_number }}</div></div></div></div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Joints of Interest : </span></div></div>
                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->interests }}</div></div></div></div>
                </div>
                
                <div class="row">
                  <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Symptoms : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->symptoms }}</div></div></div></div>
                </div>

                 <!-- <div class="row">
                  <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>
                    Note : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->notes }}</div></div></div></div>
                </div> -->
                 <div class="row">
                  <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2">
                      <div class="mb-0"><span>Documents/<br>Reports : </span></div></div><div class="col-12 col-md-9 col-lg-10"><div class="mb-0"><div class="d-inline-flex gap-1 flex-column">
                          <?php
                          if(!empty($appointment->medicalDocuments))
                          {
                              
                          $reports = explode(",",$appointment->medicalDocuments);
                          foreach($reports as $k=>$v)
                          {
                          ?>
                          <a target="_blank" href="{{ url('/') }}/public/patient_reports/{{ $v }}">{{ $v }}</a>
                          <?php
                          }
                          }
                          ?>
                          </div></div></div></div></div>
                </div>
                @if($appointment->appointment_status == "Confirmed")
                  @if($appointment->phone_meeting_link != "")    
                  <div class="row mt-3 mb-3 mb-md-0 phone_meeting_link {{ $appointment->id }}">
                      <div class="col-12 col-md-12 phone_meeting_link">
                              @if($appointment->appointmentType == "Phone Consultation")    
                              <div class="join-call d-flex align-items-center justify-content-between mb-3 mb-sm-3 mb-md-0">
                              <div class="d-flex gap-3 align-items-center mobile-text"><div class="imgs">
                                  <img src="{{ URL('/') }}/public/frontend/img/Vector(44).png"></div><div class="d-flex gap-2 align-items-center mobile-text">You will get call on:
                              <div class="bigger-text">
                                {{ $patient_phone_number }}
                              </div></div></div>
                              </div>
                      @endif

                      @if($appointment->appointmentType == "Video Consultation")                                            
                          <div class="join-call d-flex align-items-center justify-content-between mb-3 mb-sm-3 mb-md-0">
                          <div class="d-flex gap-3 align-items-center mobile-text"><div class="imgs">
                              <img src="{{ URL('/') }}/public/frontend/img/Vector(43).png"></div><div class="d-flex gap-1 flex-column">Video Call Invitation:
                          <a href="{{ $appointment->phone_meeting_link }}" target="_blank">
                              Click Here to Join Video Consultation</a></div></div>
                          <div><a target="_blank" href="{{ $appointment->phone_meeting_link }}" class="btn btn-orange border-radius-0">
                            Join Now</a></div>
                          </div>
                      @endif                                        

                      </div>
                  </div>
                  @endif
                @endif

                  @if($appointment->appointment_status == 'Completed')
                    <div class="row patent-reviews02 p-3 mt-3 pb-0">
                      <div class="col-12 col-md-3 col-lg-2">
                          <div class="mb-3"><span>
                          Doctor Notes : </span>
                          </div>
                      </div>
                      <div class="col-12 col-md-9 col-lg-10">
                          <div class="mb-3">
                          @if (!empty($appointment->notes))
                                                  {{ Crypt::decrypt($appointment->notes) }}                                                              
                                                @endif
                          </div>
                      </div>
                      <div class="col-12 col-md-3 col-lg-2">
                          <div class="mb-3"><span>
                          Prescription : </span>
                          </div>
                      </div>
                      <div class="col-12 col-md-9 col-lg-10">
                          <div class="mb-3">
                          <?php if(!empty($appointment->upload_file1)){
                              $pre = explode(",",$appointment->upload_file1);
                              foreach($pre as $k=>$prescription){ ?>
                                <a target="_blank" href="{{ url('/') }}/public/patient_reports/{{ $prescription }}">{{ $prescription }}</a><br>
                            <?php
                              }	}
                            ?>
                          </div>
                      </div>
                    </div>
                  @endif                                  
                                    
              </div>
              
              <?php
                    $booked_datetime = new DateTime($appointment->start);
                    $now = new DateTime();
                    // $booked_datetime = $booked_datetime->format("Y-m-d");
                    // date("Y-m-d")
                    $interval = new DateInterval('PT24H');
                    $datetime_after_24hrs = $now->add($interval);
                    $now_time = new DateTime();

                    $intervalTime = $now_time->diff($booked_datetime);

                    $TotalHours = ($intervalTime->days * 24) + $intervalTime->h + ($intervalTime->i / 60) + ($intervalTime->s / 3600);
                    
                    if($appointment->start < date('Y-m-d H:i:s'))
                    {
                        $TotalHours = -1;    
                    }
                  
              ?>
              <div class="col-12 col-md-3 col-lg-2" data-total_hrs ="{{ $TotalHours }}" data-app_id="{{ $appointment->id }}">
                  
              <?php if($TotalHours > 24)
                {
                        
                        if($appointment->appointment_status == 'In-Process' || $appointment->appointment_status == 'Confirmed' || $appointment->appointment_status == 'Rejected' || $appointment->appointment_status == 'Cancelled'){ ?>
                            <a data-app_id="{{ $appointment->id }}" href="{{ URL('/reschedule-appointment') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-orange mb-3 w-100 border-radius-0">
                            Reschedule</a>
                    <?php
                    }
                } ?>
          
              
                <?php if($TotalHours < 24 && $TotalHours > 0)
                {
                        if($appointment->CancelPatientOrDoctor == 2 ||    
                          $appointment->appointment_status == 'Rejected'){ ?>
                            <a data-app_id="{{ $appointment->id }}" href="{{ URL('/reschedule-appointment') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-orange mb-3 w-100 border-radius-0">
                            Reschedule</a>
                    <?php
                    }
                } ?>
                

                <!--For past appointment-->
                <?php if($appointment->start < date('Y-m-d H:i:s')){
                
                    if(($appointment->appointment_status == 'Rejected' ||
                            $appointment->appointment_status == 'Cancelled') && 
                            $appointment->CancelPatientOrDoctor == 2){ ?>
                            <a data-cancelby="{{ $appointment->CancelPatientOrDoctor }}" data-app_id="{{ $appointment->id }}" href="{{ URL('/reschedule-appointment') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-orange mb-3 w-100 border-radius-0">
                              Reschedule</a>
                              
                            <?php } elseif($appointment->appointment_status == 'In-Process' || $appointment->appointment_status == 'Cancelled'){ ?>
                                <a data-cancelby="{{ $appointment->CancelPatientOrDoctor }}" data-app_id="{{ $appointment->id }}" href="{{ URL('/reschedule-appointment') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-orange mb-3 w-100 border-radius-0">
                                Reschedule</a>
                            <?php } ?>
                    <?php

                } ?>
                
                <!--Cancel appointment-->
                <?php 
                if(($appointment->appointment_status == 'In-Process' || 
                    $appointment->appointment_status == 'Confirmed') && 
                    $result['record_type'] != 'past'
                    )
                {
                    if($TotalHours < 24 && $TotalHours > 0){ ?>
                        <a data-app_id="{{ $appointment->id }}" href="{{ URL('/cancel-appointment') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-outline-dark w-100 border-radius-0" onclick="return confirm('If you canceled this appointment, you cannot reschedule it because the appointment time is less than 24 hours away');">
                          Cancel</a>
                    <?php } else { ?>
                        <a data-app_id="{{ $appointment->id }}" href="{{ URL('/cancel-appointment') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-outline-dark w-100 border-radius-0" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                          Cancel</a>
                <?php } 
                } 
                    
                  $end_datetime_plus_1day = date('Y-m-d H:i:s',strtotime($appointment->end.' +1 Day'));  
                  $end_datetime_plus_1day = strtotime($end_datetime_plus_1day);
                      
                  if($appointment->appointment_status == 'Confirmed' || $appointment->appointment_status == 'Completed')
                  {
                      //can chat only till 24 hrs after appointment not before appointment
                      if(strtotime($appointment->end) < strtotime('now') && 
                         strtotime($appointment->end) <= $end_datetime_plus_1day
                         
                         )
                      {
                      ?>
                        
                        <form data-app_id="{{ $appointment->id }}" action="{{ route('chat.initiate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{ $appointment->doctor_id }}">
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <button type="submit" class="btn btn-orange mt-3 w-100 border-radius-0">
                              Chat with Doctor</button>
                        </form>
                        <a style="background:#02C4B7" href="{{ url('/download-pdf') }}/{{ Crypt::encrypt($appointment->id) }}" class="btn btn-success w-100 mt-3 border-radius-0">
                              Print</a>
                      <?php                    
                      }
                   }
                   ?>

                  <?php if($appointment->start < date('Y-m-d H:i:s') && $appointment->appointment_status == 'Confirmed' || $appointment->appointment_status == 'Completed')
                  { ?>
                    <a href="{{ URL('/reschedule-appointment') }}/{{ Crypt::encrypt($appointment->id) }}/{{ 'follow_up' }}" class="btn btn-orange mt-3 w-100 border-radius-0">
                    Follow Up</a>
                  <?php } ?>

                  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  
<?php

}
} else
{
?>
<div class="row m-0 No_Records_Found">
<div class="col-12 col-md-12 col-lg-12 p-4 d-flex align-items-stretch">
    <h6>No Records Found </h6>
</div>
</div>

<?php    
}
?>
<!-- <script>
  $(document).ready(function() 
  {
    var LoginSeg = '<?php //echo $LoginSegments; ?>';
    alert(LoginSeg);

    if(LoginSeg == 'login'){
      setTimeout(function() {
        window.location.href = '{{ url("/patient-dashboard") }}';
      }, 1000); // 1000 milliseconds = 1 second
    }
  });
</script> -->
</div>
</main>
</div>


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->

</body>
</html>


@endsection