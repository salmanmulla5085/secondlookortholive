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
@include('layouts.navbars.topnav', ['title' => 'Manage Testimonials'])
<div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Testimonials : <a href="{{ route('admin.testimonials.create') }}" class="btn btn-success">Add Testimonial</a></h6>
                    
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

                    
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th class="text-xs text-secondary mb-0">Sr.No</th>
                                <th class="text-xs text-secondary mb-0">Name</th>
                                <th class="text-xs text-secondary mb-0">Content</th>
                                <th class="text-xs text-secondary mb-0">Status</th>
                                
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testimonials as $t => $testimonial)
                                <tr>

                                <td>
                                        <p class="text-xs text-secondary mb-0">{{ $t+ 1 }}</p>                                         
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $testimonial->name }}</p>                                         
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ \Illuminate\Support\Str::limit($testimonial->content, 40) }}</p>                                         
                                    </td>                                    
                                    
                                    <td>
                    @if($testimonial->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>

                                    <td>
                                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="text-secondary font-weight-bold text-xs"
                                            data-toggle="tooltip" data-original-title="Edit user">
                                            Edit | 
                                        </a>

                                        
<?php /*
                                        @if($testimonial->status == 'active')
                                        <a href="{{ route('admin.testimonials.deactivate', $testimonial->id) }}" class="text-danger font-weight-bold text-xs"
                                        data-toggle="tooltip" data-original-title="Deactivate">
                                        Deactivate   
                                        </a>
                                        @endif
                                        
                                        @if($testimonial->status == 'inactive')
                                        <a href="{{ route('admin.testimonials.activate', $testimonial->id) }}" class="text-success font-weight-bold text-xs"
                                        data-toggle="tooltip" data-original-title="Activate">
                                        Activate
                                        </a>
                                        @endif
                                        */?>

                                        <?php if($testimonial->status == 'active'){ ?>
                                            <a onclick="return confirm('Are you sure you want to inactive this testimonial?'); "href="{{ route('admin.testimonials.deactivate', $testimonial->id) }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Inactive | 
                                            </a>
                                        <?php } else { ?>
                                            <a onclick="return confirm('Are you sure you want to active this testimonial?');" href="{{ route('admin.testimonials.activate', $testimonial->id)  }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Active | 
                                            </a>
                                        <?php } ?>
                                        <a onclick="return confirm('Are you sure you want to delete this testimonials?');" href="{{ url('/delete_testimonials') }}/{{ $testimonial->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
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
