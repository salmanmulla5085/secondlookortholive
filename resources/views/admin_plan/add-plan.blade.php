@extends('layouts.app')

@section('head')
<!-- CKEditor CDN -->



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
@include('layouts.navbars.topnav', ['title' => 'Add Static Page'])

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Add Category</h6>
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

                <form action="{{ URL('admin/add-category') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for='title'>{{ __(' Category name') }}</label>
                    <input type='text' class='form-control' id='category_name' name='category_name' value="{{ old('category_name') }}" required>

                    

                    <br>
                    <input type="submit" value="Save" class="btn btn-success" />
                </form>

            </div>
        </div>
    </div>
</div>
@endsection