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
    @include('layouts.navbars.topnav', ['title' => 'Add Page Section'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add Page Section</h6>
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

                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                    <form action="{{ route('admin.storePageSection') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <label for='static_page_id'>{{ __('Select Static Page') }}</label>
                        <select id="static_page_id" name="static_page_id" class="form-select form-control @error('static_page_id') is-invalid @enderror" required>
                            <option value="">Select a Static Page</option>
                            @foreach ($staticPages as $page)
                                <option value="{{ $page->id }}">{{ $page->page_name }}</option>
                            @endforeach
                        </select>
                        @error('static_page_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror


                        <label for="section_name">{{__('Section Name')}}</label>
                        <input type='text' class='form-control' id='section_name' name='section_name' required>

                        <label for="section_heading1">{{__('Section Heading 1')}}</label>
                        <input type='text' class='form-control' id='section_heading1' name='section_heading1' required>

                        <label for="section_heading2">{{__('Section Heading 2')}}</label>
                        <input type='text' class='form-control' id='section_heading2' name='section_heading2'>

                        <label for="section_short_desc1">{{__('Section Short Description 1')}}</label>                        
                        <textarea id="section_short_desc1" name="section_short_desc1" class="form-control"></textarea>
                        @error('section_short_desc1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror


                        <label for="section_short_desc2">{{__('Section Short Description 2')}}</label>                        
                        <textarea id="section_short_desc2" name="section_short_desc2" class="form-control"></textarea>
                        @error('section_short_desc2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="section_long_desc1">{{__('Section Long Description 1')}}</label>                        
                        <textarea id="section_long_desc1" name="section_long_desc1" class="form-control"></textarea>
                        @error('section_long_desc1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="section_long_desc2">{{__('Section Long Description 2')}}</label>                        
                        <textarea id="section_long_desc2" name="section_long_desc2" class="form-control"></textarea>
                        @error('section_long_desc2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="section_image1">{{__('Section Image 1')}}</label>
                        <input type="file" name="section_image1" id="section_image1" class="form-control">

                        <label for="section_image2">{{__('Section Image 2')}}</label>
                        <input type="file" name="section_image2" id="section_image2" class="form-control">

                        <label for="section_image3">{{__('Section Image 3')}}</label>
                        <input type="file" name="section_image3" id="section_image3" class="form-control">

                        <br>
                        <input type="submit" value="Save" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize CKEditor
        // CKEDITOR.replace('section_short_desc1');
        // CKEDITOR.replace('section_short_desc2');
        // CKEDITOR.replace('section_long_desc1');
        // CKEDITOR.replace('section_long_desc2');

    </script>

@endsection
