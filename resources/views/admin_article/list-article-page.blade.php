@extends('layouts.app')

@section('head')
<!-- CKEditor CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
@endsection

@section('content')
@include('layouts.navbars.topnav', ['title' => 'Manage Articles'])

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>List Article :: <a href="{{ route('page', ['page' => 'admin/add-article']) }}" class="btn btn-success">{{ __('Add') }}</a></h6>
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

                <table class="table" id="example">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($articleData)
                            @foreach ($articleData as $article)
                                <tr>
                                    <td><p class="text-xs text-secondary mb-0">{{ $article->id }}</p></td>
                                    <td><p class="text-xs text-secondary mb-0">{{ $article->title }}</p></td>
                                    <td class="align-middle text-center text-sm">
                                        @if($article->status == 'active')
                                            <span class="badge badge-sm bg-gradient-success">{{ $article->status }}</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">{{ $article->status }}</span>
                                        @endif
                                    </td>
                                    <td><p class="text-xs text-secondary mb-0">{{ date('M j Y H:i', strtotime($article->created_at)) }}</p></td>
                                    <td class="align-middle">
                                        @if($article->status == 'active')
                                            <a onclick="return confirm('Are you sure you want to inactive this article?');" href="{{ url('/article_status') }}/{{ $article->id }}/inactive" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Inactive">
                                                Inactive |
                                            </a>
                                        @else
                                            <a onclick="return confirm('Are you sure you want to active this article?');" href="{{ url('/article_status') }}/{{ $article->id }}/active" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Active">
                                                Active |
                                            </a>
                                        @endif
                                        <a href="{{ url('/edit-article') }}/{{ $article->id }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit article">
                                            Edit |
                                        </a>
                                        <a href="{{ url('/view-article-comment') }}/{{ $article->id }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View comment">
                                            View comment |
                                        </a>
                                        <a onclick="return confirm('Are you sure you want to delete this article?');" href="{{ url('/delete-article') }}/{{ $article->id }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No record found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
