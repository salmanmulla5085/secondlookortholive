@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
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
    @include('layouts.navbars.topnav', ['title' => 'Manage Doctors'])

    <?php
    $sql = "SELECT * FROM dbl_users where user_type = 'doctor' order by id desc";
    $users = DB::select($sql);
    $doctors = collect($users);					                                                          

    // $users = DB::table('users')
    // ->selectRaw('count(*) as user_count, status')
    // ->where('status', '<>', 1)
    // ->groupBy('status')
    // ->get();
    ?>
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
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Doctors:  <a href="{{ URL('add-doctor') }}" class="btn btn-success">{{__('Add')}}</a> </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table id="example" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Sr.No.</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Doctor's Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Phone#</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            #Appointments</th>    
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Admin</th>                                        
                                        <th
                                        
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Registered</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($doctors as $k => $doctor)
                                    <tr>
                                        <td class="align-middle text-center text-sm">{{ $k + 1 }}                
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ url('/').'/public/doctor_photos/'.$doctor->profile_photo }}" class="avatar avatar-sm me-3"
                                                        alt="user1">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $doctor->first_name }}  {{ $doctor->last_name }} </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                    {{ $doctor->email_address }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $doctor->phone_number }}</p>                                            
                                        </td>
                                        <?php
                                            $app_sql = "SELECT * FROM tbl_appointments_booked where doctor_id = $doctor->id";
                                            $app_users = DB::select($app_sql);
                                            $doctorsApp = collect($app_users);
                                            ?>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ count($doctorsApp) }}</p>                                            
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <?php if($doctor->admin == '1'){ ?>
                                                <span class="badge badge-sm bg-gradient-success">{{ 'Yes' }}</span>
                                            <?php } else { ?>
                                              
                                            <?php } ?>
                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            <?php if($doctor->status == 'Active'){ ?>
                                                <span class="badge badge-sm bg-gradient-success">{{ $doctor->status }}</span>
                                            <?php } else { ?>
                                                <span class="badge badge-sm bg-gradient-danger">{{ $doctor->status }}</span>
                                            <?php } ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ date('M j Y H:i', strtotime($doctor->created_at)) }}</span>
                                        </td>
                                        <td class="align-middle">

                                        <?php if($doctor->status == 'Active'){ ?>
                                            <a onclick="return confirm('Are you sure you want to inactive this doctor?'); "href="{{ url('/doctor_status') }}/{{ $doctor->id }}/0" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Inactive user">
                                                Inactive | 
                                            </a>
                                        <?php } else { ?>
                                            <a onclick="return confirm('Are you sure you want to active this doctor?');" href="{{ url('/doctor_status') }}/{{ $doctor->id }}/1" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Active user">
                                                Active | 
                                            </a>
                                        <?php } ?>

                                            <a href="{{ url('/view_doctor') }}/{{ $doctor->id }}/{{ 'view' }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="View user">
                                                View | 
                                            </a>

                                            <a href="{{ url('/view_doctor') }}/{{ $doctor->id }}/{{ 'edit' }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit | 
                                            </a>
                                             
                                             <a onclick="return confirm('Are you sure you want to delete this doctor?');" href="{{ url('/delete_doctor') }}/{{ $doctor->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Delete user">
                                                Delete
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
        </div>
        
        @include('layouts.footers.auth.footer')
    </div>
@endsection
