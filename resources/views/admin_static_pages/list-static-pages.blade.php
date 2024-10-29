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
    @include('layouts.navbars.topnav', ['title' => 'List Static Pages'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
        

        

            <div class="card mb-4">
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

        

                <div class="card-header pb-0">                    
                    <h6>List Static Pages : <a href="{{ route('page', ['page' => 'admin/add-static-page']) }}" class="btn btn-success">{{__('Add')}}</a> </h6>
                    

                </div>
                <div class="card-body px-5 pt-0 pb-2">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Page Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Page Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Meta Keyword</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Meta Description</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staticPages as $page)
                                <tr>
                                    <td class="align-middle text-center text-sm">{{ $page->id }}</td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $page->page_name }}</p>                                         
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $page->page_title }}</p>                                         
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $page->meta_keyword }}</p>                                         
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $page->meta_desc }}</p>                                         
                                    </td>
                                    <!-- Add delete functionality if needed -->
                                    <td>
                                        <a href="{{ route('admin.editStaticPage', $page->id) }}" class="text-secondary font-weight-bold text-xs"
                                            data-toggle="tooltip" data-original-title="Edit user">
                                            Edit | 
                                        </a>
                                        <form action="{{ route('admin.deleteStaticPage', $page->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <a type="submit" class="text-secondary font-weight-bold text-xs"
                                            onclick="return confirm('Are you sure you want to delete this static page?');">Delete</a>
                                        </form>
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
