@extends('layouts.app')

@section('head')
<!-- CKEditor CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            // "order": [[ 0, "desc" ]]  // 0 is the index of the ID column
        });
});
</script>
@endsection

@section('content')
@include('layouts.navbars.topnav', ['title' =>'Manage Payments'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>List Payments:
                     <!-- <a href="{{ route('page', ['page' => 'admin/add-plan']) }}" class="btn btn-success">{{__('Add')}}</a> </h6> -->
                     <div class="px-4">
                    <form id="filterForm" method="GET" action="{{ route('admin.billing') }}">
                       

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

                            <a href="{{ route('admin.billing') }}" style="background:#02C4B7" class="btn btn-success mt-4">Reset</a>
                        </div>

                        <!-- <button type="submit" class="btn btn-primary" style="display: inline;">Filter</button> -->
                    </form>



                </div>

            </div>
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
                <table class="table" id="example">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sr.No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Doctor</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tranjaction id</th>
                            <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Currency</th> -->
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @if($paymentData != null)
                        @php $i=1; @endphp
                        @foreach ($paymentData as $pay)
                        <tr>
                            <td> <p class="text-xs text-secondary mb-0">{{ $i++ }}</p>      </td>
                            <td> <p class="text-xs text-secondary mb-0">{{$pay->first_name }} {{ $pay->last_name }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ 'Dr '.$pay->doctor_first_name }} {{ $pay->doctor_last_name }}</p>   </td>

                            <td> <p class="text-xs text-secondary mb-0">{{$pay->plan_type ?? 'N/A' }}</p>   </td>                            
                            <td> <p class="text-xs text-secondary mb-0">{{$pay->txn_id}}</p>   </td>
                            <!-- <td> <p class="text-xs text-secondary mb-0">{{$pay->txn_currency}}</p>   </td> -->
                            <td> <p class="text-xs text-secondary mb-0">{{'$'.$pay->txn_amount}}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">
                                
                                @if($pay->txn_status == 'succeeded')
                                <span class="badge bg-success">Success</span>
                                @else
                                <span class="badge bg-danger">Failed</span>
                                @endif
                            </p>   
                            </td>
                            
                            <td><p class="text-xs text-secondary mb-0">{{ date('M j Y  H:i', strtotime($pay->txn_time)) }}
                            {{--  {{ \Carbon\Carbon::parse($pay->txn_time)->format('M d, Y H:i') }} --}}
                                </p>
                            </td>

                         <?php /*
                            <td class="align-middle  text-sm">
                                            <?php if($plan->status == 'Active'){ ?>
                                                <span class="badge badge-sm bg-gradient-success">{{ $pay->status }}</span>
                                            <?php } else { ?>
                                                <span class="badge badge-sm bg-gradient-danger">{{ $pay->status }}</span>
                                            <?php } ?>
                                        </td>
                                        <?php */?>
                            
                            <!-- <td> <p class="text-xs text-secondary mb-0">{{ date('j F Y H:i', strtotime($pay->created_on)) }}</p>      </td> -->
                            
                            <td class="align-middle">
                            <?php /*?>
                                        <?php if($plan->status == 'Active'){ ?>
                                            <a onclick="return confirm('Are you sure you want to inactive this plan?'); "href="{{ url('/plan_status') }}/{{ $plan->id }}/Inactive" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Inactive | 
                                            </a>
                                        <?php } else { ?>
                                            <a onclick="return confirm('Are you sure you want to active this plan?');" href="{{ url('/plan_status') }}/{{ $plan->id }}/Active" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Active | 
                                            </a>
                                        <?php } ?>
                                        

                                            <a href="{{ url('/edit-plan') }}/{{ $plan->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit 
                                            </a>
                                            */?>

<a href="{{ url('/view-billing') }}/{{ $pay->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                View
                                            </a>
                                        </td> 
                        </tr>
                        @endforeach
                        @else
                        echo "No record found."
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