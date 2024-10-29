@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
@php
    $update_appointment_id = session('update_appointment_id', []);
    $appointmentType = session('appointmentType');
    $display_payment = session('display_payment');
@endphp

<style>
    .congratulation-message {
      padding: 20px;
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      border-radius: 0.25rem;
    }
    .report-table th, .report-table td {
      text-align: left;
      vertical-align: middle;
    }
    .download-link {
      display: block;
      margin-top: 20px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-8">
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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif
        </div>        
    </div>
</div>

<div class="container mt-5">
    <!-- Congratulation Message -->
    <div class="congratulation-message">
      <h3 class="mb-3">
      @if(!empty($appointmentType) && $appointmentType == 'Report Review')
      Report Review Request @if(!empty($update_appointment_id)) Rescheduled @else Sent @endif Successfully!</h3>
      @else
      Appointment @if($cat_type != '') Booked @elseif(!empty($update_appointment_id)) Reschedule @else Booked @endif Successfully!</h3>
      @endif
    </div>

    <!-- Purchase Report -->
    <div class="card mt-4">
      <div class="card-header">
        <h5 class="card-title mb-0">Report</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered report-table">
            <tbody>
            @if($appointment->appointmentType != "Report Review")
              <tr>
                <th scope="row">Appointment Date:</th>
                <?php $start = new DateTime($appointment->start); ?>
                <td>{{ $start->format('M d, Y') }}</td>
              </tr>
              <tr>
                <th scope="row">Appointment Time:</th>
                <?php $time = new DateTime($appointment->start); ?>
                <td>{{ $time->format('G:i') }}</td>
              </tr>
            @endif

              <tr>
                <th scope="row">Doctor Name:</th>
                <td>{{ 'Dr.'. $doctor_name->first_name . ' ' . $doctor_name->last_name; }}</td>
              </tr>
              <tr>
                <th scope="row">Joints:</th>
                <td>{{ $interests }}</td>
              </tr>
              <tr>
                <th scope="row">Appointment Type:</th>
                <td>{{ $appointment->appointmentType }}</td>
              </tr>
              @if(empty($update_appointment_id) || $display_payment == '1')
                  <tr>
                    <th scope="row">Fees Paid:</th>
                    <td>{{ '$'.$appointment->amount }}</td>
                  </tr>
                  @if(!empty($payment) || $display_payment == '1')
                      <tr>
                        <th scope="row">Transaction ID:</th>
                        <td>{{ $payment->txn_id }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Transaction Status:</th>
                        <td>{{ ucfirst($payment->txn_status) }}</td>
                      </tr>
                    <tr>
                      <th scope="row">Receipt:</th>
                      <td><a href="{{ $payment->receipt_url }}" target="_blank">View Receipt</a></td>
                    </tr>
                  @endif
              @endif

             
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
@endsection
