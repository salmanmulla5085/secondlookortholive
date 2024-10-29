@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php
// echo $result['record_type'];
// die;
?>
<style>
.patent-reviews02{background:#FFEFE5;border-radius:10px; position:relative;}
.btn-disabled {
  background: #848484;
  cursor: not-allowed !important;
}
.btn-disabled:hover,.btn-disabled:focus {
  background: #848484;
  cursor: not-allowed !important;
}
.btn-uploads-doc input[type="file"]{opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;}
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
      <li class="<?= $result['record_type'] == 'report_review' ? 'active' : '' ?>">
        Report Reviews
      </li>      
  </ul>
  <a href="{{ url('/') }}/book_appointment" class="btn btn-book d-flex align-items-center gap-2"><img src="{{ url('/public/frontend/img/Layer 7.png') }}"> Book Report Review</a>
  <!-- <div>
        <input type="date" name="from_date" id="from_date" />
  
  </div> -->
  
  <!-- <a href="{{ url('/') }}/book_appointment" class="btn btn-book d-flex align-items-center gap-2"><img src="{{ url('/public/frontend/img/Layer 7.png') }}"> Book an Appointment</a> -->
  
</div>

<div class="accordion row m-0" id="accordionExample">
    
<?php 
if(!empty($result['appointments_booked']) && count($result['appointments_booked']) > 0)
{$i =0;
foreach($result['appointments_booked'] as $k=>$appointment)    
{$i++;
  
?>  
  
  <div class="col-12 col-md-12 col-lg-12 p-0 d-flex align-items-stretch">
    <div class="p-0 w-100 mt-3">
    <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
      <h2 class="accordion-header">
        <button class="accordion-button <?= ($i == 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
          <div class="patients-list mb-0 me-3 w-100">
            <ul class="list-one"><li><span>Requested On</span><?= date('j M Y G:i', strtotime($appointment->created_at)) ?></li>
            
            <li><span>Category </span>{{ $appointment->category }}</li>
            <li><span>Doctor</span>{{ 'Dr.' }} {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</li>
            </ul><span class="confirm">{{ $appointment->appointment_status }}</span>
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
                      <span>Doctor Name : </span></div></div><div class="col-12 col-md-7 col-lg-8">
                        <div class="mb-3">{{ 'Dr.' }} {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div></div></div></div>
                  <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                      <span>Charges :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">${{ $appointment->amount }} </div></div>
                      </div></div>
                </div>
                 <div class="row">
                  <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>Appointment Type :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                      <div class="mb-3">{{ $appointment->appointmentType }}</div></div></div></div>
                      <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                          <span>Contact Number :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">
                            {{ formatPhoneNumber($appointment->doctor->phone_number) }}</div></div></div></div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Joints of Interest : </span></div></div>
                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->interests }}</div></div></div></div>
                </div>
                
                <div class="row">
                  <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Symptoms : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->symptoms }}</div></div></div></div>
                </div>
                @if($appointment->notes != '')
                  <div class="row">
                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Note : </span></div></div>
                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->notes }}</div></div></div></div>
                  </div>
                @endif
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
              </div>
              
              <div class="col-12 col-md-3 col-lg-2">
                  <?php
                  $booked_datetime = new DateTime($appointment->start);
                  $now = new DateTime();
                  // $booked_datetime = $booked_datetime->format("Y-m-d");
                  // date("Y-m-d")
                  $interval = new DateInterval('PT24H');
                  $datetime_after_24hrs = $now->add($interval);                  
                    
                    
                  $end_datetime_plus_1day = date('Y-m-d H:i:s',strtotime($appointment->end.' +1 Day'));  
                  $end_datetime_plus_1day = strtotime($end_datetime_plus_1day);
                      
                  
                   ?>
                   

                  </div>
            </div>
            
            @foreach($appointment->reportReviewsReplies as $k2=>$reply)
            <div class="patent-reviews02 p-3 mt-3 pb-0">
                <div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>
                  Replied On : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3"><?= date('j M Y H:i', strtotime($reply->created_at)) ?></div></div></div>
                  <div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>
                    Doctor’s Response : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">
                  @if ($reply->doctor_reply != '')
                      {{ Crypt::decrypt($reply->doctor_reply) }}
                  @endif
                  </div></div></div>
                  <div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Doctor’s Uploads : </span></div></div>
                  <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">
                  @if(!empty($reply->upload_file1))
                        @foreach(explode(',', $reply->upload_file1) as $file)
                            <a target="_blank" href="{{ URL('/') }}/public/patient_reports/{{ trim($file) }}">{{ trim($file) }}</a><br>
                        @endforeach
                        @endif
                  </div></div></div>
            </div>
            @endforeach

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
</div>



</main>
</div>


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->

</body>
</html>

@endsection