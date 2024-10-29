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
                <h6>Add Article</h6>
            </div>
            <div class="card-body px-5 pt-0 pb-2">
            @include('layouts.navbars.topnav', ['title' => 'Add Static Page'])

                <form action="{{ URL('admin/add-article') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for='title'>{{ __('Title') }}</label>
                    <input type='text' class='form-control' id='title' name='title' value="{{ old('title') }}" required>

                    <label for="short_desc">{{ __('Short Description') }}</label>
<textarea class="form-control @error('short_desc') is-invalid @enderror" id="short_desc" name="short_desc" rows="3" >{{ old('short_desc') }}</textarea>

@error('short_desc')
    <span class="text-danger">{{ $message }}</span>
@enderror

<label for="long_desc">{{ __('Long Description') }}</label>
<textarea class="form-control" id="long_desc" name="long_desc" rows="3" >{{ old('long_desc') }}</textarea>

@if ($errors->has('long_desc'))
    <span class="text-danger">{{ $errors->first('long_desc') }}</span>
@endif

                    <label for="image">{{__('Upload Photo')}}</label>
                    <input type="file" name="image" id="image" class="form-control">

                    <label for="category_id">{{__('Select category')}}</label>
                    <select id="category_id" name="category_id" class="form-select form-control @error('category_id') is-invalid @enderror" required>
    <option value="">Select a category</option>
    @foreach($categoryData as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
            {{ $category->category_name }}
        </option>
    @endforeach
</select>

                    <br>
                    <input type="submit" value="Save" class="btn btn-success" />
                </form>

            </div>
        </div>
    </div>
</div>
@endsection