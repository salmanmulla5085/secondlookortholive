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
@include('layouts.navbars.topnav', ['title' =>'Manage Categories'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>List Categories :: <a href="{{ route('page', ['page' => 'admin/add-category']) }}" class="btn btn-success">{{__('Add')}}</a> </h6>


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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">created at</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categoryData as $category)
                        <tr>
                            <td> <p class="text-xs text-secondary mb-0">{{ $category->id }}</p>      </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ $category->category_name }}</p>   </td>
                           
                            <td class="align-middle  text-sm">
                                            <?php if($category->status == 'active'){ ?>
                                                <span class="badge badge-sm bg-gradient-success">{{ $category->status }}</span>
                                            <?php } else { ?>
                                                <span class="badge badge-sm bg-gradient-danger">{{ $category->status }}</span>
                                            <?php } ?>
                                        </td>
                            <td> <p class="text-xs text-secondary mb-0">{{ date('M j Y H:i', strtotime($category->created_at)) }}</p>      </td>

                            <td class="align-middle">

                                        <?php if($category->status == 'active'){ ?>
                                            <a onclick="return confirm('Are you sure you want to inactive this category?'); "href="{{ url('/category_status') }}/{{ $category->id }}/inactive" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Inactive | 
                                            </a>
                                        <?php } else { ?>
                                            <a onclick="return confirm('Are you sure you want to active this category?');" href="{{ url('/category_status') }}/{{ $category->id }}/active" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Active | 
                                            </a>
                                        <?php } ?>

                                            <a href="{{ url('/edit-category') }}/{{ $category->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit user">
                                                Edit | 
                                            </a>

                                            
                                             
                                             <a onclick="return confirm('Are you sure you want to delete this category?');" href="{{ url('/delete-category') }}/{{ $category->id }}" class="text-secondary font-weight-bold text-xs"
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