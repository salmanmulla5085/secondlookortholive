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
@include('layouts.navbars.topnav', ['title' => 'Manage Comments'])
<div class="row mt-4 mx-4">
    <div class="col-12">




        <div class="card mb-4">



            <div class="card-header pb-0">
                <h6>List Comments :</h6>


            </div>
            <div class="card-body px-5 pt-0 pb-2">
            @include('layouts.navbars.topnav', ['title' => 'Add Static Page'])

                <table class="table" id="example">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">user</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comment</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">created at</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $v)
                        <tr>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ $v->id }}</p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ $v->user->first_name. " ". $v->user->last_name }}</p>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ $v->comment }}</p>
                            </td>

                            <td class="align-middle text-center text-sm">
                                <?php if ($v->status == 'active') { ?>
                                    <span class="badge badge-sm bg-gradient-success">{{ $v->status }}</span>
                                <?php } else { ?>
                                    <span class="badge badge-sm bg-gradient-danger">{{ $v->status }}</span>
                                <?php } ?>
                            </td>
                            <td>
                                <p class="text-xs text-secondary mb-0">{{ date('j F Y H:i', strtotime($v->created_at)) }}</p>
                            </td>
                            <td class="align-middle">

                                <?php if ($v->status == 'active') { ?>
                                    <a onclick="return confirm('Are you sure you want to inactive this comment?'); " href="{{ url('/comment_status') }}/{{ $v->id }}/inactive" class="text-secondary font-weight-bold text-xs"
                                        data-toggle="tooltip" data-original-title="Inactive">
                                        Inactive 
                                    </a>
                                <?php } else { ?>
                                    <a onclick="return confirm('Are you sure you want to active this comment?');" href="{{ url('/comment_status') }}/{{ $v->id }}/active" class="text-secondary font-weight-bold text-xs"
                                        data-toggle="tooltip" data-original-title="Active">
                                        Active 
                                    </a>
                                <?php } ?>





                                <a onclick="return confirm('Are you sure you want to delete this article?');" href="{{ url('/delete-article') }}/{{ $v->id }}" class="text-secondary d-none font-weight-bold text-xs"
                                    data-toggle="tooltip" data-original-title="Delete">
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