@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<div class="back-to">
    <?php if($type == 'not_confirm_app'){?>
        <a href="{{url('')}}/not-confirmed-appintments"><i class="fas fa-chevron-left"></i> Back to Appointments</a>
    <?php } else { ?>
        <a href="{{url('')}}/doctor-dashboard/{{ $type }}"><i class="fas fa-chevron-left"></i> Back to Appointments</a>
    <?php } ?>
</div>
<div class="message-box">
    <div class="message-box-header">
        <div class="patients-list w-100 gap-4">
            <div class="profile-menu">
                <a class="nav-link p-0" href="#">
                    <div class="profile-pic">
                        <img src="{{ url('/public/patient_photos/') }}/{{ $AppBooked[0]->profile_photo }}" alt="Profile Picture" />
                    </div>
                    <span class="d-none d-md-flex">{{$AppBooked[0]->patient_first_name}} {{$AppBooked[0]->patient_last_name}}</span>
                    <!-- You can also use icon as follows: -->
                    <!--  <i class="fas fa-user"></i> -->
                </a>
            </div>
            <ul class="list-one">
                <li><span>Date</span>{{ \Carbon\Carbon::parse($AppBooked[0]->start)->Format('j M Y') }}</li>
                <li><span>Time</span>{{ \Carbon\Carbon::parse($AppBooked[0]->start)->Format('G:i') }}</li>
                <li><span>Category </span>{{ $AppBooked[0]->category }}</li>
            </ul>
        </div>
    </div>
    <div class="d-flex p-3 flex-column">
        <h5 class="text-dark mb-4">Medical History</h5>

        <div class="summries">
            <div class="row mb-3">
                <div class="col-12 col-md-3 col-lg-2"><span>Patient Allergies : </span></div>
                <div class="col-12 col-md-9 col-lg-10">{{ $TotalAllergies }}</div>
            </div>

            <!-- <div class="row mb-3">
                <div class="col-12 col-md-3 col-lg-2"><span>Symptoms : </span></div>
                <div class="col-12 col-md-9 col-lg-10">{{ $TotalSymtoms }}</div>
            </div> -->

            <div class="row mb-3">
                <div class="col-12 col-md-3 col-lg-2"><span>Past Medical History : </span></div>
                <div class="col-12 col-md-9 col-lg-10">{{ $TotalMedicalHis }}</div>
            </div>

            <div class="row mb-3">
                <h5 class="text-dark mb-4">Consultation History:</h5>
            </div>

            <div class="timelines">
                <ul>
                    <?php if($TotalAppBooked && !empty($TotalAppBooked) && count($TotalAppBooked) > 0 ) { 
                        foreach ($TotalAppBooked as $key => $TotalApp) {
                            ?>
                            <li class=<?php if($key == 0) { echo 'active'; } ?>>
                                <div class="timeline-date">{{ \Carbon\Carbon::parse($TotalApp->start)->Format('j M Y') }}</div>
                                <div class="timeline-content">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-0">
                                                <div class="col-12 col-md-5 col-lg-4"><span>Symptoms : </span></div>
                                                <div class="col-12 col-md-7 col-lg-8 act"><?php if($key == 0) { echo '<strong>'; } ?>@if ($TotalApp->symptoms) {{ $TotalApp->symptoms }} @else {{ '-' }} @endif<?php if($key == 0) { echo '</strong>'; } ?></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row mb-0">
                                                <div class="col-12 col-md-5 col-lg-3"><span>Consultation Type :</span></div>
                                                <div class="col-12 col-md-7 col-lg-9 act"><?php if($key == 0) { echo '<strong>'; } ?>{{ $TotalApp->appointmentType }}<?php if($key == 0) { echo '</strong>'; } ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <?php
                                        $Doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id = $TotalApp->doctor_id";
                                        $Doctors = DB::select($Doctor_sql);
                                        $DoctorData = collect($Doctors);               
                                        
                                        if($DoctorData && count($DoctorData) > 0){
                                            $doctor_name = 'Dr. '.$DoctorData[0]->first_name.' '.$DoctorData[0]->last_name;
                                        } else {
                                            $doctor_name = '';
                                        }
                                    ?>

                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-0">
                                                <div class="col-12 col-md-5 col-lg-4"><span>Doctor : </span></div>
                                                <div class="col-12 col-md-7 col-lg-8">{{ $doctor_name }}</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row mb-0">
                                                <div class="col-12 col-md-5 col-lg-3"><span>Time :</span></div>
                                                <div class="col-12 col-md-7 col-lg-9">{{ \Carbon\Carbon::parse($TotalApp->start)->Format('G:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <?php if(!empty($TotalApp->notes)){ ?>
                                        <div class="row">
                                            <div class="col">
                                                <div class="row mb-3">
                                                    <div class="col-12 col-md-2 col-lg-2"><span>Prescription :</span></div>
                                                    <div class="col-12 col-md-10 col-lg-10">
                                                        @if (!empty($TotalApp->notes))
                                                            {{ Crypt::decrypt($TotalApp->notes) }}                                                              
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if($TotalApp->medicalDocuments && $TotalApp->medicalDocuments != ''){ 
                                        $ExpDocArr = explode(',', $TotalApp->medicalDocuments);

                                        if($ExpDocArr && $ExpDocArr != ''){ ?>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row mb-0">
                                                            <?php foreach ($ExpDocArr as $file_key => $file) { ?>
                                                                <div class="col-12 col-md-5 col-lg-4"><?php if($file_key == 0) { ?><span>Documents/<br>Reports : </span><?php } ?></div>
                                                                <div class="col-12 col-md-7 col-lg-8"><a target="_blank" href="{{ url('/') }}/public/patient_reports/{{ $file }}">{{ $file }}</a></div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col d-none d-md-flex"></div>
                                                </div>
                                    <?php  } } ?>

                                    <?php if($TotalApp->upload_file1 && $TotalApp->upload_file1 != ''){ 
                                        $ExpPreArr = explode(',', $TotalApp->upload_file1);

                                        if($ExpPreArr && $ExpPreArr != ''){ ?>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row mb-0">
                                                            <?php foreach ($ExpPreArr as $pre_file_key => $pre_file) { ?>
                                                                <div class="col-12 col-md-5 col-lg-4"><?php if($pre_file_key == 0) { ?><span>Prescription file : </span><?php } ?></div>
                                                                <div class="col-12 col-md-7 col-lg-8"><a target="_blank" href="{{ url('/') }}/public/patient_reports/{{ $pre_file }}">{{ $pre_file }}</a></div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col d-none d-md-flex"></div>
                                                </div>
                                    <?php  } } ?>

                                </div>
                            </li>
                    <?php } } else { ?>
                        <div class="col-12 col-md-7 col-lg-9 act">No appointment found</div>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</main>
</div>
</body>
</html>

@endsection