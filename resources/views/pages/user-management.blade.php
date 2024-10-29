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
    @include('layouts.navbars.topnav', ['title' => 'User Management'])

    <?php
    $sql = "SELECT * FROM tbl_admins";
    $users = DB::select($sql);
    $admin_users = collect($users);					                                                          

    // $users = DB::table('users')
    // ->selectRaw('count(*) as user_count, status')
    // ->where('status', '<>', 1)
    // ->groupBy('status')
    // ->get();
    ?>

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <!-- <div class="alert alert-light" role="alert">
                <strong>
                </strong>
            </div> -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Admin Users</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table id="example" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Create Date</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($admin_users as $doctor)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ $doctor->profile_photo }}" class="avatar avatar-sm me-3"
                                                        alt="user1">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $doctor->firstname }}  {{ $doctor->lastname }} </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                    {{ $doctor->email }}</p>
                                                </div>
                                            </div>
                                        </td>                                        
                                        <td class="align-middle text-left">
                                            <span class="text-secondary text-xs font-weight-bold"> Admin</span>
                                        </td>    
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"> {{ $doctor->created_at }}</span>
                                        </td>
                                         <td class="align-middle">
                                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit 
                                            </a>
                                            <!--
                                             
                                             <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Delete
                                            </a>
                                            -->
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
@endsection
