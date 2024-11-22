@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php
// echo $result['record_type'];
// die;
?>

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
if(!empty($result['appointments_booked']))
{
foreach($result['appointments_booked'] as $k=>$appointment)    
{
?>

  <div class="col-12 col-md-2 col-lg-1 p-0 d-flex align-items-stretch"><div class="pad-15 bg-white w-100 text-center d-flex flex-column gap-1"><span class="date">{{ \Carbon\Carbon::parse($appointment->start)->Format('j F') }}</span><span class="day">
  {{ \Carbon\Carbon::parse($appointment->start)->Format('D') }}
  </span></div>
  </div>
  <div class="col-12 col-md-10 col-lg-11 p-0 d-flex align-items-stretch"><div class="pad-15 pe-0 pb-0 w-100">
    <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
      <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
          {{ $appointment->description }}
        </button>
      </h2>
      <div id="collapse_{{ $appointment->id }}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          <div class="patients-list mb-4">
            <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j F Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
            <li>
                <span>Category </span>
                @if ($appointment->category == 1)
                    New Appointment
                @elseif ($appointment->category == 2)
                    Follow Up
                @else
                    {{ $appointment->category }}
                @endif
            </li>
            <li><span>Doctor</span>{{ 'Dr. ' }} {{ $appointment->doctor_first_name }} {{ $appointment->doctor_last_name }}</li></ul><span class="confirm">{{ $appointment->appointment_status }}</span>
          </div>
          <div class="summries">
            <div class="row">
              <div class="col-12 col-md-9 col-lg-10">
                <div class="row">
                  <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                      <span>Doctor Name : </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ 'Dr. ' }} {{ $appointment->doctor_first_name }} {{ $appointment->doctor_last_name }}</div></div></div></div>
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

                 <div class="row">
                  <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Note : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->notes }}</div></div></div></div>
                </div>
                 <div class="row">
                  <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2">
                      <div class="mb-0"><span>Documents/Reports : </span></div></div><div class="col-12 col-md-9 col-lg-10"><div class="mb-0"><div class="d-inline-flex gap-1 flex-column">
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
              </div>
              <div class="col-12 col-md-3 col-lg-2">
                  <?php
                  $booked_datetime = new DateTime($appointment->start);
                  $now = new DateTime();
                  // $booked_datetime = $booked_datetime->format("Y-m-d");
                  // date("Y-m-d")
                  $interval = new DateInterval('PT24H');
                  $datetime_after_24hrs = $now->add($interval);
                  
                  if($booked_datetime > $datetime_after_24hrs)
                  {
                  ?>
                  <a href="{{ URL('/reschedule-appointment/0/') }}/{{ $appointment->id }}" class="btn btn-orange mb-3 w-100 border-radius-0">Reschedule</a>
                  <a href="{{ URL('/cancel-appointment') }}/{{ $appointment->id }}" class="btn btn-outline-dark w-100 border-radius-0">Cancel</a>
                  <?php
                  }
                  ?>
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
}
?>
</div>



</main>
</div>

<!-- Jquery -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
</body>
</html>

@endsection