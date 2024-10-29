@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Tables'])

    <?php
    $sql = "SELECT * FROM tbl_plans";
    $users = DB::select($sql);
    $plans = collect($users);					                                                          

    // $users = DB::table('users')
    // ->selectRaw('count(*) as user_count, status')
    // ->where('status', '<>', 1)
    // ->groupBy('status')
    // ->get();
    ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Plans</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Plan Type</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Plan Duration</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Plan Details</th>                                        
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>                                        
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($plans as $doctor)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $doctor->plan_type }} </h6>                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $doctor->plan_duration }}</p>                                            
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $doctor->plan_amount }}</p>                                            
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $doctor->plan_detail }}</p>                                            
                                        </td>
                                        
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm bg-gradient-success">{{ $doctor->status }}</span>
                                        </td>
                                        
                                        <td class="align-middle">
                                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit
                                            </a>
                                             
                                             <!-- <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
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
        
        @include('layouts.footers.auth.footer')
    </div>
@endsection
