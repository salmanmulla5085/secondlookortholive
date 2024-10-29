@extends('layouts.app')

@section('head')
    <!-- CKEditor CDN -->

    
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Edit Page Section'])
<?php 
$pageid = $section->static_page_id; ?>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Page Section</h6>
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
                    <form action="{{ route('admin.updatePageSection', $section->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <label for='static_page_id'>{{ __('Static Page') }}</label>
                        <select id="static_page_id" name="static_page_id" class="form-select form-control @error('static_page_id') is-invalid @enderror" required>
                            @foreach($staticPages as $page)
                                <option value="{{ $page->id }}" {{ $section->static_page_id == $page->id ? 'selected' : '' }}>{{ $page->page_name }}</option>
                            @endforeach
                        </select>
                        @error('static_page_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!--section 1 -->
                        <h4 class="mt-2">{{ __('Section 1') }}</h4>
                        
                        <label for="section_name">{{ __('Section Name') }}</label>
                        <input type='text' class='form-control' id='section_name' name='section_name' value="{{ $section->section_name }}" required>
                        <label for="section_heading1">{{ __('Section Heading 1') }}</label>
                        <input type='text' class='form-control' id='section_heading1' name='section_heading1' value="{{ $section->section_heading1 }}" required>
                        <label for="section_short_desc1">{{__('Section Short Description 1')}}</label>                        
                        <textarea id="section_short_desc1" name="section_short_desc1" class="form-control">{{ $section->section_short_desc1 }}</textarea>
                        @error('section_short_desc1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(!empty($section->section_long_desc1))
                        <label for="section_long_desc1">{{__('Section Long Description 1')}}</label>                        
                        <textarea id="section_long_desc1" name="section_long_desc1" class="form-control">{{ $section->section_long_desc1 }}</textarea>
                        @error('section_long_desc1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @endif
                        @if(!empty($section->$section->section_image1))
                        <label for="section_image1">{{ __('Section Image 1') }}</label>
                        <input type="file" name="section_image1" id="section_image1" class="form-control">
                        @if($section->section_image1)
                        <div class="mt-3">
                            <img src="{{ URL('/') }}/public/{{ $section->section_image1 }}" alt="Section Image 1" class="img-fluid" style="max-width: 200px;">
                        </div>
                        @endif
                        @endif

                        @if($pageid == 1 || $pageid == 6  )
                       <!--section 2 -->
                       <h4 class="mt-4">{{ __('Section 2') }}</h4>
                        <label for="section_heading2">{{ __('Section Heading 2') }}</label>
                        <input type='text' class='form-control' id='section_heading2' name='section_heading2' value="{{ $section->section_heading2 }}">
                        <label for="section_short_desc2">{{__('Section Short Description 2')}}</label>                        
                        <textarea id="section_short_desc2" name="section_short_desc2" class="form-control">{{ $section->section_short_desc2 }}</textarea>
                        @error('section_short_desc2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <label for="section_long_desc2">{{__('Section Long Description 2')}}</label>                        
                        <textarea id="section_long_desc2" name="section_long_desc2" class="form-control">{{ $section->section_long_desc2 }}</textarea>
                        @error('section_long_desc2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <label for="section_image2">{{ __('Section Image 2') }}</label>
                        <input type="file" name="section_image2" id="section_image2" class="form-control">
                        @if($section->section_image2)
                        <div class="mt-3">
                            <img src="{{ URL('/') }}/public/{{ $section->section_image2 }}" alt="Section Image 2" class="img-fluid" style="max-width: 200px;">
                        </div>
                        @endif
                        @endif
                        @if($pageid == 6)
                       <!--section 3-->
                       <h4 class="mt-4">{{ __('Section 3') }}</h4>
                         <label for="section_short_desc2">{{__('Section Short Description 3')}}</label>                        
                        <textarea id="section_short_desc3" name="section_short_desc3" class="form-control">{{ $section->section_short_desc3 }}</textarea>
                        @error('section_short_desc3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @endif
                       
                        @if($pageid == 1)
                        <label for="section_heading3">{{ __('Section Heading 3') }}</label>
                        <input type='text' class='form-control' id='section_heading3' name='section_heading3' value="{{ $section->section_heading3 }}">
                        <label for="section_image3">{{ __('Section Image 3') }}</label>
                        <input type="file" name="section_image3" id="section_image3" class="form-control">
                        @if($section->section_image3)
                        <div class="mt-3">
                            <img src="{{ URL('/') }}/public/{{ $section->section_image3 }}" alt="Section Image 3" class="img-fluid" style="max-width: 200px;">
                        </div>
                        @endif

                        @endif


                        <!-- <label for="section_image7">{{ __('Section Image 8') }}</label>
                        <input type="file" name="section_image8" id="section_image8" class="form-control">
                        @if($section->section_image8)
                        <div class="mt-3">
                            <img src="{{ URL('/') }}/public/{{ $section->section_image8 }}" alt="Section Image 8" class="img-fluid" style="max-width: 200px;">
                        </div>
                        @endif -->

                        <br>
                        <input type="submit" value="Update" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
