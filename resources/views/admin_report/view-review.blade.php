@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Manage Report Review'])
    <div class="">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="row mt-4 mx-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Report Review Request</h6>
                </div>
                <div class="card-body px-5 pt-0 pb-2">
                    @csrf
                    <label for="doctor_name">{{ __('Doctor Name') }}</label>
                    <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="Dr. {{ $schedule->doctor->first_name }} {{ $schedule->doctor->last_name }}" required disabled>

                    <label for="patient_name">{{ __('Patient Name') }}</label>
                    <input type="text" class="form-control" id="patient_name" name="patient_name" value="{{ $schedule->patient->first_name }} {{ $schedule->patient->last_name }}" required disabled>

                    <label for="amount">{{ __('Amount') }}</label>
                    <input type="text" class="form-control" id="amount" name="amount" value="${{ $schedule->amount }}" required disabled>

                    <label for="status">{{ __('Status') }}</label>
                    <input type="text" class="form-control" id="status" name="status" value="{{ $schedule->status }}" required disabled>

                    <label for="joint_of_interest">{{ __('Joint of Interest') }}</label>
                    <input type="text" class="form-control" id="interests" name="interests"  value="{{ $schedule->interests }}"  required disabled>

                    <label for="symptoms">{{ __('Symptoms') }}</label>
                    <input type="text" class="form-control" id="symptoms" name="symptoms" value="{{ $schedule->symptoms }}" required disabled>

                    <label for="patient_contact">{{ __('Patient Contact Number') }}</label>
                    <input type="text" class="form-control" id="patient_contact" name="patient_contact" value="{{ $schedule->contactNumber }}" required disabled>
                    <br>
                    <label for="documents">{{ __('Documents/Reports') }}</label>
                    <div id="documents">
                        @foreach (explode(',', $schedule->medicalDocuments) as $file)
                            <a href="{{ URL('/') }}/public/patient_reports/{{ trim($file) }}" target="_blank">{{ trim($file) }}</a>@if (!$loop->last), @endif
                        @endforeach
                    </div>

                    
                </div>
            </div>
        </div>
        
        @foreach($schedule->reportReviewsReplies as $k2=>$reply)
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Reply from Doctor</h6>
                </div>
                <div class="card-body px-5 pt-0 pb-2">
                    <p><strong>Replied On:</strong><?= date('j M Y H:i', strtotime($reply->created_at)) ?></p>
                    <p><strong>Doctor’s Response:</strong>{{ $reply->doctor_reply }}</p>
                    <p><strong>Doctor’s Uploads:</strong>
                    @if(!empty($reply->upload_file1))
                        @foreach(explode(',', $reply->upload_file1) as $file)
                            <a target="_blank" href="{{ URL('/') }}/public/patient_reports/{{ trim($file) }}">{{ trim($file) }}</a><br>
                        @endforeach
                    @endif
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection