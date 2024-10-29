@extends('layouts.app')

@section('head')
<!-- CKEditor CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "order": [[ 0, "desc" ]]  // 0 is the index of the ID column
        });
    });
</script>
@endsection

@section('content')
@include('layouts.navbars.topnav', ['title' =>'Manage Report Review'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>List Report Reviews : </h6>
                <br>
                <div class="px-4">
                    <form id="filterForm" method="GET" action="{{ route('admin.review') }}">
                       

                        <div class="d-flex align-items-center gap-1">
                            <div class="form-group ">
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
                            <div class="form-group">
                                <label for="start" class="mb-0">From Date</label>
                                <input type="text" name="start" id="start-picker" placeholder="mm-dd-yyyy" class="form-control @error('start') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();" value="{{ request('start') }}">
                            </div>

                            <div class="form-group">
                                <label for="end" class="mb-0">To Date</label>
                                <input type="text" name="end" id="end-picker" placeholder="mm-dd-yyyy" class="form-control @error('end') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();" value="{{ request('end') }}">
                            </div>

                            <div class="form-group">
                                <label for="status" class="mb-0">Status</label>
                                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" style="width: 150px;" onchange="document.getElementById('filterForm').submit();">
                                    <option value="" selected>@lang('Select Status')...</option>
                                    <option value="Replied" {{ request('status') == 'Replied' ? 'selected' : '' }}>Replied</option>
                                    <option value="Not-Replied" {{ request('status') == 'Not-Replied' ? 'selected' : '' }}>Not Replied</option>
                                </select>
                            </div>
                            <a href="{{ route('admin.review') }}" style="background:#02C4B7" class="btn btn-success mt-4">Reset</a>
                        </div>

                        <!-- <button type="submit" class="btn btn-primary" style="display: inline;">Filter</button> -->
                    </form>



                </div>
<!-- old code  -->
                <div class="d-none">
                <form id="filterForm" method="GET" action="{{ route('admin.review') }}">
                    <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" autocomplete="doctor" style="display: inline;width:20%" onchange="document.getElementById('filterForm').submit();">
                        <option value="" selected>@lang('Select Doctor')...</option>
                        @foreach($result['doctors'] as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ 'Dr. ' . ucfirst(strtolower($doctor->first_name)) . ' ' . ucfirst(strtolower($doctor->last_name)) }}

                            </option>
                        @endforeach
                    </select>

                    <select id="selectedStatus" name="status" class="form-control @error('status') is-invalid @enderror" autocomplete="status" style="display: inline;width:20%" onchange="document.getElementById('filterForm').submit();">
                        <option value="" selected>@lang('Select Status')...</option>
                        <option value="Replied" {{ request('status') == 'Replied' ? 'selected' : '' }}>Replied</option>
                        <option value="Not-Replied" {{ request('status') == 'Not-Replied' ? 'selected' : '' }}>Not Replied</option>
                    </select>

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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">category</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">created at</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviewData as $review)
                        <tr>
                            <td> <p class="text-xs text-secondary mb-0">{{ $review->id }}</p>      </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $review->appointmentType }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ 'Dr.'.$review->doctor->first_name }} {{ $review->doctor->last_name }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $review->patient->first_name }} {{ $review->patient->last_name }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ '$'.$review->amount }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $review->category }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $review->status }}</p>   </td>

                          
                            <td> <p class="text-xs text-secondary mb-0">{{ date('M j Y  H:i', strtotime($review->created_at)) }}</p>      </td>

                            <td class="align-middle">

                                       

                                           
                                            <a href="{{ url('/view-review') }}/{{ $review->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="view">
                                                view 
                                            </a>

                                            
                                             
                                             
                                        </td>
                        </tr>
                        @endforeach
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