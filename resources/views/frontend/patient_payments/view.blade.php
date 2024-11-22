@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

<div class="box-main p-3 bg-white margin-15-b radius8">
  <h4 class="text-center mt-3 text-dark"></h4>
  <div class="row">
    <div class="col-md-6">
      <ul class="list-group">
        <li class="list-group-item"><strong>Patient Name:</strong> {{ $payment->first_name }} {{ $payment->last_name }}</li>
        <li class="list-group-item"><strong>Plan Type:</strong> {{ $payment->plan_type ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Transaction ID:</strong> {{ $payment->txn_id }}</li>
        <!-- <li class="list-group-item"><strong>Amount:</strong> {{ $payment->txn_amount/100 }}</li> -->
        <!-- <li class="list-group-item"><strong>Status:</strong> {{ $payment->txn_status }}</li> -->
        <!-- <li class="list-group-item"><strong>Transaction Time:</strong> {{ $payment->txn_time }}</li> -->
        <li class="list-group-item"><strong>Receipt URL:</strong> <a href="{{ $payment->receipt_url }}" target="_blank">View Receipt</a></li>
        @if($payment->txn_status != 'succeeded')
        <li class="list-group-item"><strong>Failure Code:</strong> {{ $payment->txn_failure_code }}</li>
        <li class="list-group-item"><strong>Failure Message:</strong> {{ $payment->txn_failure_message }}</li>
        @endif
      </ul>
    </div>
    <div class="col-md-6">
      <ul class="list-group">
        <!-- <li class="list-group-item"><strong>Plan ID:</strong> {{ $payment->plan_id }}</li> -->
        <li class="list-group-item"><strong>Patient ID:</strong> {{ $payment->patient_id }}</li>
        <li class="list-group-item"><strong>Transaction Amount:</strong>${{ $payment->txn_amount }}</li>
        <li class="list-group-item"><strong>Transaction Time:</strong> {{ \Carbon\Carbon::parse($payment->txn_time)->format('M d, Y H:i') }}</li>
        <li class="list-group-item"><strong>Payment Status:</strong> {{ $payment->txn_status }}</li>
      </ul>
    </div>
  </div>
</div>


</body>
</html>

@endsection
