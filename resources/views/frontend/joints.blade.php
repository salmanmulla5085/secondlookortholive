@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Manage Joints'])

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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder align-middle text-center opacity-7">
                                            Sr.No.</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Page Name</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($joints as $k => $joint)
                                    <tr>
                                        <td class="align-middle text-center text-sm">{{ $k + 1 }}</td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $joint->page_name }}</p>                                            
                                        </td>
                                        <td class="align-middle">

                                            <a href="{{ url('/view_joint') }}/{{ $joint->id }}/{{ 'view' }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="View Joint">
                                                View | 
                                            </a>

                                            <a href="{{ url('/view_joint') }}/{{ $joint->id }}/{{ 'edit' }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit
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
