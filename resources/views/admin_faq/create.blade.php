@extends('layouts.app')
@section('head')

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
@endsection
@section('content')
@include('layouts.navbars.topnav', ['title' => 'Add FAQ'])

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Add FAQ</h6>
            </div>

            <div class="card-body px-5 pt-0 pb-2">
                <form action="{{ route('admin.faq.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" class="form-control" id="question" name="question" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Add FAQ</button>
                </form>
            </div>
        </div>
    </div>
</div>        
@endsection
