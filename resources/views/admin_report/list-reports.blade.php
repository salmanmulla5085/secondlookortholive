@extends('layouts.app')

@section('head')
<!-- CKEditor CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            // "order": [
            //     [0, "desc"]
            // ] // 0 is the index of the ID column
        });
    });
</script>
@endsection

@section('content')
@include('layouts.navbars.topnav', ['title' =>'Manage Reports'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>List Reports : </h6>
                <br>
               



                <div class="px-4">
                    <form id="filterForm" method="GET" action="{{ route('admin.reports') }}">
                        <!-- <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" autocomplete="doctor" style="display: inline;width:20%" onchange="document.getElementById('filterForm').submit();">
                        <option value="" selected>@lang('Select Doctor')...</option>
                        @foreach($result['doctors'] as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ 'Dr. ' . ucfirst(strtolower($doctor->first_name)) . ' ' . ucfirst(strtolower($doctor->last_name)) }}

                            </option>
                        @endforeach
                        </select> -->


                        <!-- new design for the  input boxes  -->

                        <div class="d-flex align-items-center gap-1">
                            <div class="form-group">
                                <label for="doctor_id" class="mb-0">Doctor Name</label>
                                <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();">
                                    <option value="" selected>@lang('Select Doctor')...</option>
                                    @foreach($result['doctors'] as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ 'Dr. ' . ucfirst(strtolower($doctor->first_name)) . ' ' . ucfirst(strtolower($doctor->last_name)) }}

                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="start" class="mb-0">From Date</label>
                                <input type="date" name="start" id="start" class="form-control @error('start') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();" value="{{ request('start') }}">
                            </div>

                            <div class="form-group">
                                <label for="end" class="mb-0">To Date</label>
                                <input type="date" name="end" id="end" class="form-control @error('end') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();" value="{{ request('end') }}">
                            </div> -->
                            <div class="form-group">
                                <label for="start" class="mb-0">From Date</label>
                                <input type="text" name="start" id="start-picker" placeholder="mm-dd-yyyy" class="form-control @error('start') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();" value="{{ request('start') }}">
                            </div>

                            <div class="form-group">
                                <label for="end" class="mb-0">To Date</label>
                                <input type="text" name="end" id="end-picker" placeholder="mm-dd-yyyy" class="form-control @error('end') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();" value="{{ request('end') }}">
                            </div>



                            <div class="form-group">
                                <label for="appointmentType" class="mb-0">Appointment Type</label>
                                <select id="appointmentType" name="appointmentType" class="form-control @error('appointmentType') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();">
                                    <option value="" selected>@lang('Select Appointment type')...</option>
                                    <!-- <option value="Report Review" {{ request('appointmentType') == 'Report Review' ? 'selected' : '' }}>Report Review</option> -->
                                    <option value="Phone Consultation" {{ request('appointmentType') == 'Phone Consultation' ? 'selected' : '' }}>Phone Consultation</option>
                                    <option value="Video Consultation" {{ request('appointmentType') == 'Video Consultation' ? 'selected' : '' }}>Video Consultation</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status" class="mb-0">Status</label>
                                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();">
                                    <option value="" selected>@lang('Select Status')...</option>
                                    <option value="Replied" {{ request('status') == 'Replied' ? 'selected' : '' }}>Replied</option>
                                    <option value="Not-Replied" {{ request('status') == 'Not-Replied' ? 'selected' : '' }}>Not Replied</option>
                                    <option value="In-Process" {{ request('status') == 'In-Process' ? 'selected' : '' }}>In Process</option>
                                    <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Expired" {{ request('status') == 'Expired' ? 'selected' : '' }}>Expired</option>


                                </select>
                            </div>
                            <a href="{{ route('admin.reports') }}" style="background:#02C4B7" class="btn btn-success mt-4">Reset</a>
                        </div>

                        <!-- <button type="submit" class="btn btn-primary" style="display: inline;">Filter</button> -->
                    </form>



                </div>
            </div>
            <br>
            <div class="card-body px-5 pt-0 pb-2">
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
                <br>
                <table class="table" id="example">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Appointment Type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Doctor Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Appointment Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Appointment Time</th>
                            <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment</th> -->
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @if($reviewData != null && $reviewData->isNotEmpty())
                        @foreach ($reviewData as $k => $review)
                        @php
                        // Initialize variables
                        $startTime = '';
                        $endTime = '';

                        // Check and separate start date and time
                        if (!empty($review->start) && strpos($review->start, ' ') !== false) {
                        list($startdate, $starttime) = explode(' ', $review->start);
                        $startTime = substr($starttime, 0, 5); // Get HH:MM
                        }

                        // Check and separate end date and time
                        if (!empty($review->end) && strpos($review->end, ' ') !== false) {
                        list($enddate, $endtime) = explode(' ', $review->end);
                        $endTime = substr($endtime, 0, 5); // Get HH:MM
                        }
                        @endphp
                        <tr>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ $k + 1 }}</p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ $review->appointmentType }}</p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">
                                    @if($review->doctor)
                                    {{ 'Dr. ' . ($review->doctor->first_name ?? '') . ' ' . ($review->doctor->last_name ?? '') }}

                                    @endif
                                </p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">
                                    @if($review->patient)
                                    {{ $review->patient->first_name ?? ''}} {{ $review->patient->last_name ?? ''}}
                                    @endif
                                </p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ date('M j Y', strtotime($review->start)) }}
                                     <!-- {{ $review->start ? date('M j Y', strtotime($startdate)) : '' }} -->
                                    </p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0"> {{ $startTime .' - '.$endTime }}</p>
                            </td>
                            <?php /*<td>
                                <p class="text-xs text-secondary mb-0">{{ $review->payment_id ? 'Yes' : 'No' }}</p>                            
                            </td> */ ?>
                            <td>
                                <p class="text-xs text-secondary mb-0">
                                    @if ($review->payment)
                                    {{ '$'.$review->payment->txn_amount ?? ''}}
                                    @endif
                                </p>
                            </td>

                            <td>
                                <p class="text-xs text-secondary mb-0">{{ $review->status ?? '' }}</p>
                            </td>

                        </tr>
                        @endforeach


                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#start-picker").datepicker({
            dateFormat: "mm-dd-yy" // Set the date format
        });
        $("#end-picker").datepicker({
            dateFormat: "mm-dd-yy" // Set the date format
        });
    });
    </script>

@endsection