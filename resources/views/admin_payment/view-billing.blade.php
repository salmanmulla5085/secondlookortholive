@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'View Payment'])

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>View Payment</h6>
                </div>
                <div class="card-body px-5 pt-0 pb-2">
                    @csrf
                    <div class="form-group">
                        <label for="patient_id">{{ __('Patient ID') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->patient_id }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="patient_name">{{ __('Patient Name') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->first_name }} {{ $payment->last_name }}" disabled>
                    </div>

                    
                    <div class="form-group">
                        <label for="plan_type">{{ __('Plan Type') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->plan_type ?? 'N/A' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="txn_id">{{ __('Transaction ID') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_id }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="txn_status">{{ __('Transaction Status') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_status }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="txn_amount">{{ __('Transaction Amount') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_amount }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="txn_currency">{{ __('Transaction Currency') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_currency }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="txn_time">{{ __('Transaction Time') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_time }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="receipt_url">{{ __('Transaction Receipt URL') }}</label>
                        <a href="{{ $payment->receipt_url }}" target="_blank" class="d-block">Payment Receipt</a>
                    </div>

                    @if($payment->txn_status != 'succeeded')
                    <div class="form-group">
                        <label for="txn_failure_code">{{ __('Failure Code') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_failure_code }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="txn_failure_message">{{ __('Failure Message') }}</label>
                        <input type="text" class="form-control" value="{{ $payment->txn_failure_message }}" disabled>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
