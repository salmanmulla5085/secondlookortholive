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
    @include('layouts.navbars.topnav', ['title' => 'Edit Static Page'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Static Page</h6>
                </div>
                <div class="card-body px-5 pt-0 pb-2">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.updateStaticPage', ['id' => $staticPage->id]) }}" method="POST">
                        @csrf
                        @method('POST')

                        <label for="page_name">{{ __('Page Name') }}</label>
                        <input type="text" class="form-control" id="page_name" name="page_name" value="{{ $staticPage->page_name }}" required>

                        <label for="page_title">{{ __('Page Title') }}</label>
                        <input type="text" class="form-control" id="page_title" name="page_title" value="{{ $staticPage->page_title }}" required>

                        <label for="meta_keyword">{{ __('Meta Keyword') }}</label>
                        <input type="text" class="form-control" id="meta_keyword" name="meta_keyword" value="{{ $staticPage->meta_keyword }}" required>

                        <label for="meta_desc">{{ __('Meta Description') }}</label>
                        <input type="text" class="form-control" id="meta_desc" name="meta_desc" value="{{ $staticPage->meta_desc }}" required>

                        <br>
                        <input type="submit" value="Update" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
