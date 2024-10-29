@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php
if($extData && count($extData) > 0){
    $i =0;
    foreach($extData as $k=>$appointment)    
    {
        $i++;

        if($user_type == 'patient'){
            $Doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id = $appointment->doctor_id";
            $users = DB::select($Doctor_sql);
            $DoctorData = collect($users); 
            
            $name_title = 'Doctor Name';
            
            if($DoctorData && count($DoctorData) > 0){
                $full_name = 'Dr. '.$DoctorData[0]->first_name.' '.$DoctorData[0]->last_name;
            } else {
                $full_name = '';
            }

            $patientFlagSql = "SELECT * FROM messages where app_id = $appointment->id AND sender_id = $appointment->doctor_id AND msg_flag = 0";
            $patientFlagSql = DB::select($patientFlagSql);
            $FlagSql = collect($patientFlagSql); 

        } else {
            $Patient_sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id = $appointment->patient_id";
            $users = DB::select($Patient_sql);
            $PatientData = collect($users);               
            
            $name_title = 'Patient Name';

            if($PatientData && count($PatientData) > 0){
                $full_name = $PatientData[0]->first_name.' '.$PatientData[0]->last_name;
            } else {
                $full_name = '';
            }

            $doctorFlagSql = "SELECT * FROM messages where app_id = $appointment->id AND sender_id = $appointment->patient_id AND msg_flag = 0";
            $doctorFlagSql = DB::select($doctorFlagSql);
            $FlagSql = collect($doctorFlagSql); 

        }
    ?>
        <div data-id="{{ $appointment->id }}" class="message1 bg-white p-3 d-flex justify-content-space-between gap-3 mb-3">
            <ul class="list-one w-100">
                <li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j M Y') }}</li>
                <li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
                <li><span>Category </span>{{ $appointment->category }}</li>
                <li><span>{{ $name_title }}</span>{{ $full_name }}</li>
            </ul>
            <form data-app_id="{{ $appointment->id }}" action="{{ route('chat.initiate') }}" method="POST">
                @csrf
                @if($user_type == 'patient')
                    <input type="hidden" name="doctor_id" value="{{ $appointment->doctor_id }}">
                @else
                    <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                @endif
                <input type="hidden" name="from_msg" value="1">
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                @if(count($FlagSql) > 0)<button class="msg_btn" type="submit" style="background: #ecf3f2 !important;"><img src="{{ url('/public/frontend/img/Vector(37).png') }}" /></button>@else<button class="msg_btn" type="submit" style="background: #ecf3f2 !important;"><img src="{{ url('/public/frontend/img/Vector(38).png') }}" /></button>@endif
            </form>
        </div>
<?php } } else { ?>
    <div class="bg-white p-3 d-flex justify-content-space-between gap-3 mb-3">
        No message found.
    </div>
<?php } ?>

@if($pagination == 1)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            {{-- Previous Page Link --}}
            @if ($extData->onFirstPage())
                <li class="page-item disabled prev">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @else
                <li class="page-item prev">
                    <a class="page-link" href="{{ $extData->previousPageUrl() }}">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($extData->links()->elements[0] as $page => $url)
                @if ($page == $extData->currentPage())
                    <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($extData->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $extData->nextPageUrl() }}">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @else
                <li class="page-item disabled next">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif


@endsection