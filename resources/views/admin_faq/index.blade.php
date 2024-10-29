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
@include('layouts.navbars.topnav', ['title' => 'Manage FAQs'])

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>FAQs : <a href="{{ route('admin.faq.create') }}" class="btn btn-success">Add</a></h6>
            </div>

            <div class="card-body px-5 pt-0 pb-2">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table id="example" class="table">
                    <thead>
                        <tr>
                             <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sr.No</th>
                             <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Question</th>
                             <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                             <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created Date</th>
                             <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faqs as $k => $faq)
                            <tr>
                            <td>
                            <p class="text-xs text-secondary mb-0">    
                            
                                {{ $k + 1  }}</td></p>
                                <td>
                                <p class="text-xs text-secondary mb-0">    
                                {{ $faq->question }}</p>
                            </td>
                                <td class="align-middle text-center text-sm">
                                    <?php if($faq->status == '1'){ ?>
                                        <span class="badge badge-sm bg-gradient-success">{{ 'Active' }}</span>
                                    <?php } else { ?>
                                        <span class="badge badge-sm bg-gradient-danger">{{ 'Inactive' }}</span>
                                    <?php } ?>
                                </td>
                                <td>
                                <p class="text-xs text-secondary mb-0">    
                                {{ date('M j Y', strtotime($faq->created_at)) }}
                                   </p></td>
                                <td>
                                    <?php if($faq->status == '1'){ ?>
                                        <a onclick="return confirm('Are you sure you want to inactive this faq?'); "href="{{ url('admin/faq_status') }}/{{ $faq->id }}/0" class="text-secondary font-weight-bold text-xs">
                                            Inactive |
                                        </a>
                                    <?php } else { ?>
                                        <a onclick="return confirm('Are you sure you want to active this faq?');" href="{{ url('admin/faq_status') }}/{{ $faq->id }}/1" class="text-secondary font-weight-bold text-xs">
                                            Active |
                                        </a>
                                    <?php } ?>

                                    <a href="{{ route('admin.faq.edit', $faq->id) }}" class="text-secondary font-weight-bold text-xs">Edit |</a>
                                    <?php /*<form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a type="submit" class="text-secondary font-weight-bold text-xs">Delete </a>
                                    </form>
                                    <?php */?>
                                    <a onclick="return confirm('Are you sure you want to delete this faq?');" href="{{ url('/delete_faq') }}/{{ $faq->id }}" class="text-secondary font-weight-bold text-xs"
                                                data-toggle="tooltip" data-original-title="Edit faq">
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
