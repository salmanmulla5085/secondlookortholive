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
@include('layouts.navbars.topnav', ['title' =>'Manage Contact Messages'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>Message List :
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">First Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">created at</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messageData as $key => $msg)
                        <tr>
                            <td> <p class="text-xs text-secondary mb-0">{{ $key+1 }}</p>      </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $msg->first_name }}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $msg->last_name}}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $msg->email}}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $msg->phone}}</p>   </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ date('M j Y H:i', strtotime($msg->created_at)) }}</p>      </td>

                            <td class="align-middle">
                                <a href="{{ url('/view-message') }}/{{ $msg->id }}" class="text-secondary font-weight-bold text-xs"
                                    data-toggle="tooltip" data-original-title="Edit user">
                                    View |
                                </a>
                                <a onclick="return confirm('Are you sure you want to delete this contact?');" href="{{ url('/delete-contact') }}/{{ $msg->id }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete">
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
@endsection