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

.patent-reviews02 {
  background: #FFEFE5;
  position: relative;
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
  flex-direction: column;
  gap: 1rem;
}
.mobile-text {
  flex-direction: column;
}
}


</style>
<!-- <div class="modal fade" id="doctor_prescription-patientModal" tabindex="-1" 
 aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter Prescription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="replyForm" action="{{ URL('/save_doctor_prescription') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="completed_appointment_id" id="completed_appointment_id" value="">
          <div class="mb-3">
            <textarea class="form-control" name="doctor_prescription" name="doctor_prescription" 
            placeholder="Enter prescription..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Mark as Complete</button>

          </div>
        </form>
      </div>
    </div>
  </div>
</div> -->

<div class="modal fade" id="doctor_prescription-patientModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter Prescription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="replyForm" action="{{ URL('/save_doctor_prescription') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="completed_appointment_id" id="completed_appointment_id" value="">
          <div class="mb-3">
            <textarea class="form-control" name="doctor_prescription" id="doctor_prescription" 
            placeholder="Enter prescription..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
      
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document
              <input type="file" name="upload_file1[]" id="medicalDocuments" multiple style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div>
          <div class="uploaded-files-new d-flex flex-column gap-2">
              <!-- Uploaded files will be dynamically inserted here -->
          </div>

          <!-- <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document-2
              <input type="file" name="upload_file2" id="upload_file2" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document-3
              <input type="file" name="upload_file3" id="upload_file3" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div> -->
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Mark as Complete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modifyreply-patientModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Modify Prescription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="modifyreplyForm" action="{{ route('doctor.modify_doctor_prescription') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="modify_appointment_id" id="modify_appointment_id">
          
          <div class="mb-3">
            <input type="hidden" id="ExtmedicalDocuments" value=""/>
            <textarea class="form-control" name="modify_doctor_prescription" id="modify_doctor_prescription" maxlength="400" placeholder="" style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div> 
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document
              <input type="file" name="modify_upload_file1[]" id="modify_upload_file1" multiple style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div> 
          <div class="uploaded-files d-flex flex-column gap-2">
              <!-- Uploaded files will be dynamically inserted here -->
          </div>      
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="enter_meeting_info_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter Phone Number OR Meeting Link</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="replyForm" action="{{ URL('/confirm_appointment_v3_post') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="confirm_appointment_id" id="confirm_appointment_id" value="">
          <div class="mb-3">
            <textarea class="form-control" id="phone_meeting_link" name="phone_meeting_link" placeholder="Enter phone number or meeting_link..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
          
          <!-- <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document-2
              <input type="file" name="upload_file2" id="upload_file2" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div>
          <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary btn-uploads-doc position-relative ps-4 pe-4">
              <i class="fas fa-file-upload"></i> Upload Document-3
              <input type="file" name="upload_file3" id="upload_file3" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
            </button>
          </div> -->
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Confirm</button>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>

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
                             
<?php //echo'<pre>';print_r($CheckRedFlag);die;?>
                             
<div class="book-bg">
  <ul>
  
      <li class="<?= $result['record_type'] == 'todays' ? 'active' : '' ?>">
        <a href="{{ url('/') }}/doctor-dashboard/todays">Today’s Appointments</a>
      </li>
      
      <li class="<?= $result['record_type'] == 'upcoming' ? 'active' : '' ?>">
          <a href="{{ url('/') }}/doctor-dashboard/upcoming">Upcoming Appointments</a>
      </li>

      <li class="<?= $result['record_type'] == 'new' ? 'active' : '' ?>">
        <a href="{{ url('/') }}/doctor-dashboard/new"><div class="position-relative">New Appointments
        <?php
        if(isset($CheckRedFlag) && count($CheckRedFlag) > 0)
        {
        ?>
        <span class="disc"></span>            
        <?php
        }
        ?>
        </div>
        </a>
      </li>
      
      <li class="<?= $result['record_type'] == 'past' ? 'active' : '' ?>">
          <a href="{{ url('/') }}/doctor-dashboard/past">Past Appointments</a>
      </li>
            
      <li class="<?= $result['record_type'] == 'rejected' ? 'active' : '' ?>">
          <a href="{{ url('/') }}/doctor-dashboard/rejected">Rejected/Cancelled Appointments</a>
      </li>
      
  </ul>
  
  

  <!-- <a href="{{ url('/') }}/book_appointment" class="btn btn-book d-flex align-items-center gap-2"><img src="{{ url('/public/frontend/img/Layer 7.png') }}"> Book an Appointment</a> -->
  
</div>

<form id="DoctorDashboardForm" action="{{ url('/') }}/doctor-dashboard/{{ $result['record_type'] }}" method="POST">
  @csrf 
  <div class="row mt-3">
    <div class="col-md-3 mb-3">
      <label for="start">{{__('Start Date')}}</label>
      <input type="text" class='form-control small_cls' name="start" id="start-picker" placeholder="mm-dd-yyyy" required value='{{ @$start }}'> 
      <!-- <input type='date' class='form-control small_cls' id='start' name='start' required value='{{ @$start }}'> -->
    </div>
    <div class="col-md-3 mb-3">
      <label for="end">{{__('End Date')}}</label>
      <input type="text" class='form-control small_cls' name="end" id="end-picker" placeholder="mm-dd-yyyy" required value='{{ @$end }}'> 
      <!-- <input type='date' class='form-control small_cls' id='end' name='end' required value='{{ @$end }}'> -->
    </div>


    <div class="col-md-3 mb-3">
      <label>Status</label>                       
      <select class="form-select small_cls" id="status" name="status">
          <option value="">Select Status</option>
          @if(@$result['record_type'] == 'todays' || @$result['record_type'] == 'upcoming')
            <option value="Confirmed" <?php if(@$status == 'Confirmed') { echo 'selected'; } ?>>Confirmed</option>
          @elseif(@$result['record_type'] == 'new')
            <option value="In-Process" <?php if(@$status == 'In-Process') { echo 'selected'; } ?>>In Process</option>
          @elseif(@$result['record_type'] == 'past')
            <option value="In-Process" <?php if(@$status == 'In-Process') { echo 'selected'; } ?>>Expired</option>
            <option value="Completed" <?php if(@$status == 'Completed') { echo 'selected'; } ?>>Completed</option>
            <option value="Confirmed" <?php if(@$status == 'Confirmed') { echo 'selected'; } ?>>Confirmed</option>
          @elseif(@$result['record_type'] == 'rejected')
            <option value="Rejected" <?php if(@$status == 'Rejected') { echo 'selected'; } ?>>Rejected</option>
            <option value="Cancelled" <?php if(@$status == 'Cancelled') { echo 'selected'; } ?>>Cancelled</option>
          @endif
      </select>
    </div>
    <div class="col-md-3 mb-3" style="margin-top: 32px;">
      <button type="submit" name="btnSubmit" id="submit" class="btn btn-orange border-radius-0">Submit</button>
      <a href="{{ url('/') }}/doctor-dashboard/{{ $result['record_type'] }}" style="background:#02C4B7" class="btn btn-success border-radius-0">Reset</a>
    </div>
  </div>
</form>

<div class="modal fade" id="app-rejectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span class="reason"></span> Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="reasonForm" action="" method="POST">
          @csrf
          <div class="mb-3">
            <textarea class="form-control" name="reason" placeholder="Enter your reason..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
        
    <?php 
    if(!empty($result['appointments_booked']) && $result['appointments_booked']->isNotEmpty())
    {
        ?>
        <div class="accordion row m-0" id="accordionExample">
            <?php
            $i =0;
            foreach($result['appointments_booked'] as $k=>$appointment)    
            {
                $i++;

                $Patient_sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id = $appointment->patient_id";
                $users = DB::select($Patient_sql);
                $PatientData = collect($users);               
                
                if($PatientData && count($PatientData) > 0){
                    $patient_name = $PatientData[0]->first_name.' '.$PatientData[0]->last_name;
                    $patient_phone_number = $PatientData[0]->phone_number;
                } else {
                    $patient_name = $patient_phone_number = '';
                }
            ?>

                <div class="col-12 col-md-2 col-lg-1 p-0 d-flex align-items-stretch" data-appointment-id="{{$appointment->id }}">
                    <div class="pad-15 bg-white w-100 text-center d-flex flex-column gap-1">
                        <span data-appointment-id="{{$appointment->id }}" class="date">{{ \Carbon\Carbon::parse($appointment->start)->Format('j M') }}</span><span class="day">
                    {{ \Carbon\Carbon::parse($appointment->start)->Format('D') }}
                    </span>
                    </div>
                </div>
                <div class="col-12 col-md-10 col-lg-11 p-0 d-flex align-items-stretch">
                    <div class="pad-15 pe-0 pb-0 w-100">
                        <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
                            <h2 class="accordion-header">
                            <button class="accordion-button <?= ($i == 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
                                <div class="patients-list mb-0 me-3 w-100">
                                    <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j M Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
                                    <li><span>Category </span>{{ $appointment->category }}</li>
                                    <li><span>Patient</span>{{ $patient_name }} </li></ul><span class="confirm">@if($appointment->appointment_status == 'In-Process' && $appointment->start < date('Y-m-d H:i:s')) {{ 'Expired' }} @else {{ $appointment->appointment_status }} @endif</span>
                                </div>
                            </button>
                            </h2>
                            <div id="collapse_{{ $appointment->id }}" class="accordion-collapse collapse <?= ($i == 1) ? 'show' : '' ?>" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <!--div class="patients-list mb-4">
                                <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j F Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
                                <li><span>Category </span>{{ $appointment->category }}</li>
                                <li><span>Patient</span>{{ $appointment->patient_name }} </li></ul><span class="confirm">{{ $appointment->appointment_status }}</span>
                                </div-->
                                <div class="summries">
                                <div class="row">
                                    <div class="col-12 col-md-9 col-lg-10">
                                    <div class="row">
                                        <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                                            <span>Patient Name : </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $patient_name }} </div></div></div></div>
                                        <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                                            <span>Charges :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">${{ $appointment->amount }} </div></div>
                                            </div></div>
                                    </div>
                                        <div class="row">
                                        <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>Consultation Type :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                                            <div class="mb-3">{{ $appointment->appointmentType }}</div></div></div></div>
                                            <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                                                <span>Contact Number :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $patient_phone_number }}</div></div></div></div>
                                    </div>

                                    <!--<div class="row">-->
                                    <!--    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Joints of Interest : </span></div></div>-->
                                    <!--    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->interests }}</div></div></div></div>-->
                                    <!--</div>-->
                                    <div class="row">
                                        <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Symptoms : </span></div></div>
                                        <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->symptoms }}</div></div></div></div>
                                    </div>
                                    <!-- <div class="row" style="display:none;">
                                        <div class="col-12 col-md-12">
                                            <div class="row">
                                                <div class="col-12 col-md-3 col-lg-2">
                                                    <div class="mb-3">
                                                        <span>Note : </span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-9 col-lg-10">
                                                    <div class="mb-3">{{ $appointment->notes }}</div>
                                                </div>
                                            </div>
                                        </div>
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
                                    <div class="row mt-3 mb-3 mb-md-0">
                                        <div class="col-12 col-md-12">
                                            <div class="d-flex gap-2 align-items-center"><img src="{{ url('/public/frontend/img/Vector(41).png') }}" /> <a href="{{ url('/patient-history') }}/{{ Crypt::encrypt($appointment->id) }}/{{ Crypt::encrypt($appointment->patient_id) }}/{{ $result['record_type'] }}">
                                                View Patient History</a></div>
                                        </div>
                                    </div>
                                    
                                    @if($appointment->appointment_status == "Confirmed")
                                      @if($appointment->phone_meeting_link != "")    
                                        <div class="row mt-3 mb-3 mb-md-0 phone_meeting_link {{ $appointment->id }}">
                                          <div class="col-12 col-md-12 phone_meeting_link">
                                            @if($appointment->appointmentType == "Phone Consultation")    
                                            <div class="join-call d-flex align-items-center justify-content-between mb-3 mb-sm-3 mb-md-0">
                                            <div class="d-flex gap-3 align-items-center mobile-text"><div class="imgs">
                                                <img src="{{ URL('/') }}/public/frontend/img/Vector(44).png"></div><div class="d-flex gap-2 align-items-center mobile-text">You can call on:
                                            <div class="bigger-text"><a href="#">
                                            <!-- Call from following phone number to patient at the time of appointment.<br>     -->
                                            {{ $appointment->phone_meeting_link }}</a></div></div></div>
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
                      
                                    <!-- <div class="row patent-reviews02 p-3 mt-3 pb-0" style="margin-left:4px">
                                        <div class="col-12 col-md-3 col-lg-2">
                                            <div class="mb-3"><span>
                                            Doctor Notes : </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-9 col-lg-10">
                                            <div class="mb-3">
                                            {{ $appointment->notes }}
                                            </div>
                                        </div>
                                    </div>                                               -->
                                            
                                    @if($appointment->appointment_status == "Completed")
                                    <div class="patent-reviews02 p-3 mt-3 pb-0">
                                            <div class="row">
                                                <div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>
                                                Date/Time : </span></div></div>
                                                <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">
                                                <?= date('j M Y H:i', strtotime($appointment->completed_at)) ?>  
                                                </div></div></div>
                                                <div class="row"><div class="col-12 col-md-3 col-lg-2">
                                                  <div class="mb-3"><span>
                                                    Doctor’s Prescription : </span></div></div>
                                                <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">
                                                @if (!empty($appointment->notes))
                                                  {{ Crypt::decrypt($appointment->notes) }}                                                              
                                                @endif
                                                </div></div></div>
                                                <div class="row">
                                                    <div class="col-12 col-md-3 col-lg-2">
                                                    <div class="mb-3">
                                                    <span>Doctor’s Uploads : </span>
                                                    </div>
                                                    </div>
                                                    <div class="col-12 col-md-6 col-lg-8">
                                                    <div class="mb-3">
                                                    
                                                    @if(!empty($appointment->upload_file1))
                                                    @foreach(explode(',', $appointment->upload_file1) as $file)
                                                        <a target="_blank" href="{{ URL('/') }}/public/patient_reports/{{ trim($file) }}">{{ trim($file) }}</a><br>
                                                    @endforeach
                                                    @endif
                                
                                                    </div>
                                                    </div>

                                                    <div class="col-12 col-md-3 col-lg-2" style="">
                                                    <div class="mb-3"> 
                                                        <button type="button" class="btn btn-orange w-100 border-radius-0" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modifyreply-patientModal"  
                                                            data-modify-appointment-id="{{ $appointment->id }}" 
                                                            data-modify-reply-id = "{{ $appointment->id }}" 
                                                            data-modify-reply-text="
                                                             @if (!empty($appointment->notes))
                                                                  {{ Crypt::decrypt($appointment->notes) }}                                                              
                                                              @endif
                                                            " 
                                                            data-modify-medical-documents-id="{{ $appointment->upload_file1 }}" 
                                                            >                              
                                                            Modify Prescription
                                                        </button>                   
                                                    </div>
                                                    </div>

                            
                                                </div>
                                    </div>                           
                                    @endif

                            </div>
                                    <?php /*<div class="col-12 col-md-3 col-lg-2">
                                        <?php
                                        $booked_datetime = new DateTime($appointment->start);
                                        $now = new DateTime();
                                        // $booked_datetime = $booked_datetime->format("Y-m-d");
                                        // date("Y-m-d")
                                        $interval = new DateInterval('PT24H');
                                        $datetime_after_24hrs = $now->add($interval);                    

                                        if($appointment->appointment_status == "In-Process")
                                        {
                                        ?>
                                        <button type="button" data-appointment_status = "{{ $appointment->appointment_status }}" data-appointment-id = "{{ $appointment->id }}" class="accept_appointment btn btn-primary w-100 border-radius-0">Accept</button>                    
                                        <?php
                                        }
                                        
                                        if($booked_datetime > $datetime_after_24hrs)
                                        {
                                        ?>
                                        <!-- <a href="{{ URL('/reschedule-appointment') }}/{{ $appointment->id }}" class="btn btn-orange mb-3 w-100 border-radius-0">Reschedule</a> -->
                                        <!-- <a data-appointment-id = "{{ $appointment->id }}" class="accept_appointment btn btn-primary mb-3 w-100 border-radius-0">Accept</a> -->                                      
                                        <?php                    
                                        if($result['record_type'] != "rejected")
                                        {
                                            // echo $result['record_type'];
                                        ?>
                                        <a href="{{ URL('/doctor-cancel-appointment') }}/{{ $appointment->id }}" class="btn btn-outline-dark w-100 border-radius-0">Cancel</a>                   
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        }                    
                                        // $start_date = date('Y-m-d', strtotime($appointment->start));
                                        $end_datetime_plus_1day = date('Y-m-d H:i:s',strtotime($appointment->end.' +1 Day'));  
                                        $end_datetime_plus_1day = strtotime($end_datetime_plus_1day);

                                        
                                        if($result['record_type'] != "rejected" && $appointment->appointment_status == 'Confirmed')
                                        {
                                        //can chat only till 24 hrs after appointment not before appointment
                                        if(strtotime($appointment->end) < strtotime('now') && strtotime($appointment->end) <= $end_datetime_plus_1day)
                                            {
                                        ?>
                                            <!-- <form action="" method="POST">
                                                @csrf
                                                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                                                <button type="submit" class="btn btn-primary">Chat Now with Patient</button>
                                            </form> -->
                                        <?php                    
                                            }
                                        }
                                        ?>
                                        </div>*/?>
                                        
                                        
                                    <div class="col-12 col-md-3 col-lg-2">

                                        <!-- <a href="#" class="btn btn-orange btn-disabled w-100 border-radius-0 mb-3">Reply</a> -->
                                        
                                        <?php 
                                        if($appointment->start < date("Y-m-d H:i:s") && $appointment->appointment_status == "Confirmed" && !empty($result['record_type']) && ($result['record_type'] == 'past' || $result['record_type'] == 'todays'))
                                        {?>
                                        <button data-app_start = "{{ $appointment->start }}" data-now = "{{ NOW() }}" id="mark_as_complete_btn"
                                        type="button" style="background-color: #02C4B7;" class="btn btn-success mb-3 w-100 border-radius-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#doctor_prescription-patientModal"
                                                data-completed_appointment_id="{{ $appointment->id }}">
                                            Mark as Completed
                                        </button>
                                        <?php
                                        }?>

                                        <?php if(!empty($result['record_type']) && $result['record_type'] == 'new')
                                        {?>
                                        <button id="confirm_btn"
                                        type="button" style="background-color: #02C4B7;" class="btn btn-success mb-3 w-100 border-radius-0 confirm_btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#enter_meeting_info_Modal"
                                                data-appointment-id="{{ $appointment->id }}"
                                                data-appointmentType="{{ $appointment->appointmentType }}"
                                                data-phone_no="{{ $patient_phone_number }}"
                                                >
                                            Confirm
                                        </button>
                                        <?php
                                        }?>

                                        
                                        <?php if(!empty($result['record_type']) && $result['record_type'] == 'new'){ ?>
                                            <!-- <a id="confirm_btn" href="{{ URL('/confirm-appointment/confirm') }}/{{ $appointment->id }}" 
                                            class="btn btn-orange mb-3 w-100 border-radius-0" 
                                            >
                                            Confirm
                                            </a> -->
                                            <!-- onclick="return confirm('Do you want to confirm this appointment?')" -->
                                            
                                        <a class="btn btn-outline-dark w-100 border-radius-0" data-bs-toggle="modal" data-bs-target="#app-rejectionModal" 
                                        data-appointment-id="{{ $appointment->id }}" data-appointment-status="{{ 'reject' }}">Reject</a>
                                        <?php } elseif(!empty($result['record_type']) && $result['record_type'] == 'todays' || !empty($result['record_type']) && $result['record_type'] == 'upcoming')
                                              {                                                 
                                                    if(!empty($appointment->appointment_status  != 'Completed'))
                                                    {                                                
                                                    ?>
                                                    <!--<a href="#" class="btn btn-orange mb-3 w-100 border-radius-0">Reschedule</a>-->
                                                    <a data-app_start = "{{ $appointment->start }}" data-now = "{{ NOW() }}" class="btn btn-outline-dark w-100 border-radius-0" data-bs-toggle="modal" data-bs-target="#app-rejectionModal" data-appointment-id="{{ $appointment->id }}" data-appointment-status="{{ 'cancel' }}" >Cancel</a>
                                                    <?php
                                                    }
                                                    ?>
                                        <?php } elseif(!empty($result['record_type']) && $result['record_type'] == 'past'){ ?>
                                            <!--<a href="#" class="btn btn-orange mb-3 w-100 border-radius-0">View Messages</a>-->
                                        <?php } elseif(!empty($result['record_type']) && $result['record_type'] == 'rejected'){ ?>
                                            <!--<a href="#" class="btn btn-orange mb-3 w-100 border-radius-0">Reschedule</a>-->
                                            <!--<a href="#" class="btn btn-outline-dark w-100 border-radius-0" onclick="return confirm('Do you want to delete this appointment?')">Delete</a>-->
                                        <?php }
                                        
                                        
                                        $end_datetime_plus_1day = date('Y-m-d H:i:s',strtotime($appointment->end.' +1 Day'));  
                                        $end_datetime_plus_1day = strtotime($end_datetime_plus_1day);
                                        
                                        if($appointment->appointment_status == 'Confirmed' || $appointment->appointment_status == 'Completed')
                                        {
                                        //can chat only till 24 hrs after appointment not before appointment
                                        if($appointment->start < date("Y-m-d H:i:s"))
                                            {
                                        ?>
                                            <form action="{{ route('chat.initiate') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                                                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                                <button type="submit" class="btn btn-orange mt-3 w-100 border-radius-0">Chat with Patient</button>
                                            </form>
                                        <?php                    
                                            }
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
            ?>
        </div>  
        <?php
    }    
    else
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


</main>
</div>


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->

</body>
</html>

<script>

    $(function() {
        $("#start-picker").datepicker({
            dateFormat: "mm-dd-yy" // Set the date format
        });
        $("#end-picker").datepicker({
            dateFormat: "mm-dd-yy" // Set the date format
        });
    });
   
    $(document).ready(function() { 
        
        
    
        $('#enter_meeting_info_Modal').on('show.bs.modal', function(event) {
                
                var button = $(event.relatedTarget); // Button that triggered the modal
                
                var confirm_appointment_id = button.data('appointment-id'); // Extract info from data-* attributes
                var confirm_appointmenttype = button.data('appointmenttype'); // Extract info from data-* attributes
                var phone_no = button.data('phone_no');

                var modal = $(this);
                
                $("#confirm_appointment_id").val(confirm_appointment_id);
                
                
                if(confirm_appointmenttype == "Phone Consultation")
                {
                  $("#phone_meeting_link").val(phone_no);
                }
                // Make AJAX call to generate Zoom meeting link
                if(confirm_appointmenttype == "Video Consultation")
                {
                    // Clear the textarea initially
                    // $("#phone_meeting_link").val(''); 
                    
                    // Disable the textarea and buttons
                    $("#phone_meeting_link").prop('disabled', true);
                    $(".modal-footer button").prop('disabled', true); // Disable the footer buttons
                    
                    // Add blur class to modal body (optional)
                    $(".modal-body").addClass('blur-content');
                    
                    $.ajax({
                            url: '{{ URL("/") }}/generate-zoom-meeting-link', // Laravel route for the controller
                            type: 'POST',
                            data: {
                                _token: $('input[name="_token"]').val(), // CSRF Token
                                appointment_id: confirm_appointment_id
                            },
                            success: function(response) {
                                // Set the response (Zoom meeting link) to the textarea
                                console.log(response);
                                if(response.error) {
                                    alert(response.error);
                                }
                                
                                if(response.join_url) {
                                    $("#phone_meeting_link").val(response.join_url); // Insert the Zoom join URL into the textarea
                                } else {
                                    alert('Failed to generate Zoom meeting link.');
                                }
                                
                                // Enable the textarea and buttons again
                                $("#phone_meeting_link").prop('disabled', false);
                                $(".modal-footer button").prop('disabled', false); // Enable the footer buttons
                    
                                // Remove blur class
                                $(".modal-body").removeClass('blur-content');
                            },
                            error: function(xhr) {
                                alert('Error generating Zoom meeting link.');
                                console.log('Error:', xhr);
                            }
                    });
                }

                
                // Add a submit event handler to the form
                modal.find('form').on('submit', function(event) {
                    // Show confirmation dialog
                    if (!confirm('Are you sure you want to proceed?')) {
                        // Prevent form submission if user cancels
                        event.preventDefault();
                    }
                });
                
        });
    
        $('#doctor_prescription-patientModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var appointmentId = button.data('completed_appointment_id'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#completed_appointment_id').val(appointmentId);
        });

        $('#modifyreply-patientModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modify_appointment_id = button.data('modify-appointment-id'); // Extract info from data-* attributes
        // var modify_reply_id = button.data('modify-reply-id'); // Extract info from data-* attributes
        var ext_medical_doc = button.data('modify-medical-documents-id');
        
        var modify_doctor_prescription = button.data('modify-reply-text'); // Extract info from data-* attributes
        
        var modal = $(this);

        modal.find('#modify_appointment_id').val(modify_appointment_id);
        modal.find('#modify_doctor_prescription').val(modify_doctor_prescription);
        modal.find('#ExtmedicalDocuments').val(ext_medical_doc);
        // modal.find('#modify_reply_id').val(modify_reply_id);

        const uploadedFilesContainer = document.querySelector('.uploaded-files');
        const serverpath = "{{ URL('/') }}/public/patient_reports/";
        var ExtmedicalDocuments = $('#ExtmedicalDocuments').val();

            if(ExtmedicalDocuments != null && ExtmedicalDocuments != '' && typeof ExtmedicalDocuments !== 'undefined'){
                var medicalDocumentsArray = ExtmedicalDocuments.split(",");
                var fileInput = medicalDocumentsArray;
                
                const files = fileInput;
                uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

                Array.from(files).forEach(file => {
                
                    const fileName = file;

                    // Create a new file item
                    const fileItem = document.createElement('div');
                    fileItem.className = 'd-flex gap-2 align-items-center';
                    
                    const closeButton = document.createElement('a');
                    closeButton.href = '#';
                    closeButton.className = 'close extClose';
                    closeButton.innerHTML = `<img class="existing_files" data-filename="${fileName}" src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;
                    
                    const fileLink = document.createElement('a');
                    fileLink.href = `${serverpath}${fileName}`;
                    fileLink.textContent = fileName;
                    fileLink.target = "_blank";  
                    fileItem.appendChild(closeButton);
                    fileItem.appendChild(fileLink);
                    uploadedFilesContainer.appendChild(fileItem);

                    closeButton.addEventListener('click', function(event) {
                        event.preventDefault();
                        

                        const filename = this.querySelector('img').getAttribute('data-filename');
                        const appointmentId = button.data('modify-appointment-id'); 

                        // Perform AJAX request to delete the file
                        fetch('{{ route("deleteprescriptionfile") }}', {
                        method: 'POST',
                        headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                        appointmentId: appointmentId,
                        filename: filename
                        })
                        })
                        .then(response => response.json())
                        .then(data => {
                        if (data.success) {
                        alert('File deleted successfully.');
                        fileItem.remove();
                        } else {
                        alert('Failed to delete file.');
                        }
                        })
                        .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the file.');
                        });

                    });

                });
            }
        });

        $('#mark_as_complete_btn').on('click', function() {
            completed_appointment_id = $(this).data("completed_appointment_id");
            // alert(confirm_appointment_id);
            $("#completed_appointment_id").val(completed_appointment_id);
            return true;
        });
      
        $('#app-rejectionModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var appointmentId = button.data('appointment-id'); // Extract info from data-* attributes
                var status = button.data('appointment-status'); // Extract info from data-* attributes
                var modal = $(this);
                if(status == 'reject'){
                    modal.find('.reason').html('Reject');

                    // Update the form action URL
                    var form = modal.find('form');
                    var newActionUrl = "{{ URL('/confirm-appointment/reject') }}/" + appointmentId;
                    form.attr('action', newActionUrl);

                } else {
                    modal.find('.reason').html('Cancel');
                    // Update the form action URL
                    var form = modal.find('form');
                    var newActionUrl = "{{ URL('/confirm-appointment/cancel') }}/" + appointmentId;
                    form.attr('action', newActionUrl);
                }
                // Add a submit event handler to the form
                modal.find('form').on('submit', function(event) {
                    // Show confirmation dialog
                    if (!confirm('Are you sure you want to proceed?')) {
                        // Prevent form submission if user cancels
                        event.preventDefault();
                    }
                });
            });

            $('.accept_appointment').on('click', function() {
                
                const appointmentId = $(this).data('appointment-id'); // Assuming you set the appointment ID in the modal's data
                const appointment_status = $(this).data('appointment_status'); // Assuming you set the appointment ID in the modal's data
                const url = "{{ route('appointments.confirm') }}";
                const button = $(this); // Store a reference to the clicked button

                // if(appointment_status == 'Confirmed')
                //     {
                //     $('#accept_appointment').text(status);
                //     $('#accept_appointment').removeClass("btn-primary");
                //     $('#accept_appointment').addClass("btn-success");
                //     }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF token for security
                        appointment_id: appointmentId
                    },
                    success: function(response) {
                        if (response.success) {
                            
                            // Change button label to 'Confirmed'
                            button.text('Confirmed');
                            // Change button class to 'btn-success'
                            button.removeClass('btn-primary').addClass('btn-success');
                        } else {
                            alert('Failed to confirm appointment: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error in confirming the appointment.');
                    }
                });
            });
    });

    

    document.addEventListener('DOMContentLoaded', function() {
      var fileInput = document.getElementById('medicalDocuments');
      const uploadedFilesContainer = document.querySelector('.uploaded-files-new');
      let PushedImg = []; // Ensure PushedImg is declared.

      fileInput.addEventListener('change', function() {
          const files = fileInput.files;

          // Iterate over the files and add them to PushedImg
          Array.from(files).forEach(file => {
              PushedImg.push(file);

              var dataTransfer = new DataTransfer();

              // Re-add files from PushedImg to the input
              PushedImg.forEach(imgFile => dataTransfer.items.add(imgFile));

              // Set the files to the input field
              fileInput.files = dataTransfer.files;

              const fileName = file.name;

              // Create a new file item
              const fileItem = document.createElement('div');
              fileItem.className = 'd-flex gap-2 align-items-center';

              const closeButton = document.createElement('a');
              closeButton.href = '#';
              closeButton.className = 'close';
              closeButton.innerHTML = `<img src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;
              closeButton.addEventListener('click', function(event) {
                  event.preventDefault();
                  
                  // Remove file from PushedImg array
                  const index = PushedImg.indexOf(file);
                  if (index > -1) {
                      PushedImg.splice(index, 1); // Remove the file from the array
                  }

                  // Create a new DataTransfer and update with the new PushedImg array
                  var updatedDataTransfer = new DataTransfer();
                  PushedImg.forEach(imgFile => updatedDataTransfer.items.add(imgFile));

                  // Set the updated files to the input field
                  fileInput.files = updatedDataTransfer.files;

                  // Remove the file item from the display
                  fileItem.remove();
              });

              const fileLink = document.createElement('a');
              fileLink.href = '#';
              fileLink.textContent = fileName;

              fileItem.appendChild(closeButton);
              fileItem.appendChild(fileLink);
              uploadedFilesContainer.appendChild(fileItem);
          });
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      var fileInput = document.getElementById('modify_upload_file1');
      const uploadedFilesContainer = document.querySelector('.uploaded-files');
      let PushedImg = []; // Ensure PushedImg is declared.

      fileInput.addEventListener('change', function() {
          const files = fileInput.files;

          // Iterate over the files and add them to PushedImg
          Array.from(files).forEach(file => {
              PushedImg.push(file);

              var dataTransfer = new DataTransfer();

              // Re-add files from PushedImg to the input
              PushedImg.forEach(imgFile => dataTransfer.items.add(imgFile));

              // Set the files to the input field
              fileInput.files = dataTransfer.files;

              const fileName = file.name;

              // Create a new file item
              const fileItem = document.createElement('div');
              fileItem.className = 'd-flex gap-2 align-items-center';

              const closeButton = document.createElement('a');
              closeButton.href = '#';
              closeButton.className = 'close';
              closeButton.innerHTML = `<img src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;
              closeButton.addEventListener('click', function(event) {
                  event.preventDefault();
                  
                  // Remove file from PushedImg array
                  const index = PushedImg.indexOf(file);
                  if (index > -1) {
                      PushedImg.splice(index, 1); // Remove the file from the array
                  }

                  // Create a new DataTransfer and update with the new PushedImg array
                  var updatedDataTransfer = new DataTransfer();
                  PushedImg.forEach(imgFile => updatedDataTransfer.items.add(imgFile));

                  // Set the updated files to the input field
                  fileInput.files = updatedDataTransfer.files;

                  // Remove the file item from the display
                  fileItem.remove();
              });

              const fileLink = document.createElement('a');
              fileLink.href = '#';
              fileLink.textContent = fileName;

              fileItem.appendChild(closeButton);
              fileItem.appendChild(fileLink);
              uploadedFilesContainer.appendChild(fileItem);
          });
      });
    });


</script>
@endsection