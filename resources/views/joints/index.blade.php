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
    @include('layouts.navbars.topnav', ['title' => 'Manage Joints'])
    <style>
        .success_msg {
            margin-left: 23px;
            margin-right: 23px;
        }
    </style>
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
            <div class="alert alert-success success_msg">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table id="example" class="table align-items-center mb-0">
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
