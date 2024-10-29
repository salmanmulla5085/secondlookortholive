@extends('layouts.app')

@section('head')
<!-- CKEditor CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
@endsection

@section('content')
@include('layouts.navbars.topnav', ['title' =>'Manage Plans'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>List Plans :
                     <!-- <a href="{{ route('page', ['page' => 'admin/add-plan']) }}" class="btn btn-success">{{__('Add')}}</a> </h6> -->


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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan type</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan duration</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Plan amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">created at</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($planData as $plan)
                        <tr>
                            <td> <p class="text-xs text-secondary mb-0">{{ $plan->id }}</p>      </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $plan->plan_type }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $plan->plan_duration}}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $plan->plan_amount}}</p>   </td>

                            <td class="align-middle  text-sm">
                                            <?php if($plan->status == 'Active'){ ?>
                                                <span class="badge badge-sm bg-gradient-success">{{ $plan->status }}</span>
                                            <?php } else { ?>
                                                <span class="badge badge-sm bg-gradient-danger">{{ $plan->status }}</span>
                                            <?php } ?>
                                        </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ date('M j Y H:i', strtotime($plan->created_at)) }}</p>      </td>

                            <td class="align-middle">

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

                                            
                                             
                                             <!-- <a onclick="return confirm('Are you sure you want to delete this plan?');" href="{{ url('/delete-plan') }}/{{ $plan->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Delete
                                            </a> -->
                                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection