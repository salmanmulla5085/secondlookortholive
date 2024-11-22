<?php if($title == 'view'){ 
    $title = 'View';
    $disabled = 'disabled';
} elseif($title == 'edit') { 
    $title = 'Edit';
    $disabled = '';
}?>
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
    @include('layouts.navbars.topnav', ['title' => 'Manage Joints'])
        

        <div class="">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
                
            </ul>
            
        </div>
        @endif
    
        @if (session('success'))
            <div class="alert alert-success success_msg">
                {{ session('success') }}
            </div>
        @endif
    </div>
        <div class="row mt-4 mx-4">
            <div class="col-12">
                    <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>{{ $title }} Joint</h6>
                            </div>
                            <div class="card-body px-5 pt-0 pb-2">
                            <form action="{{ URL('/update-joint') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="ExtJointId" name="ExtJointId" value="{{ $JointData[0]->id }}">
                                <input type="hidden" id="ExtJointname" name="ExtJointname" value="{{ $JointData[0]->name }}">
                                <label for="page_name">{{ __('Page Name') }}</label>
                                <input type="text" class="form-control @error('page_name') is-invalid @enderror" id="page_name" name="page_name" value="{{ old('page_name', $JointData[0]->page_name) }}" {{ $disabled }} required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <h4 class="mt-4">{{ __('Section 1') }}</h4>
                                <label for="heading1">{{__('Section Heading 1')}}</label>
                                <input type="text" class="form-control @error('heading1') is-invalid @enderror" id="heading1" name="heading1" value="{{ old('heading1', $JointData[0]->heading1) }}" {{ $disabled }}  required>
                                @error('heading1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="content1">{{__('Section Description 1')}}</label>                        
                                <textarea id="content1" name="content1" class="form-control">{{ $JointData[0]->content1 }}</textarea>
                                @error('content1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <?php if($title && $title == 'Edit'){ ?>
                                    <label for="photo1">{{__('Section Image 1')}}</label>
                                    <input type="file" name="photo1" id="photo1" class="form-control">
                                <?php } ?>  
                                @if(!empty($JointData[0]->photo1))
                                    <label for="photo1">{{ __('Photo 1') }}</label>
                                    <div class="mt-3">
                                        <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo1 }}" alt="Photo 1" class="img-fluid" style="max-width: 20%!important;">
                                    </div>
                                @endif
                                <hr>
                                <h4 class="mt-4">{{ __('Section 2') }}</h4>
                                <label for="heading2">{{__('Section Heading 2')}}</label>
                                <input type="text" class="form-control @error('heading2') is-invalid @enderror" id="heading2" name="heading2" value="{{ old('heading2', $JointData[0]->heading2) }}" {{ $disabled }}  required>
                                @error('heading2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="content2">{{__('Section Description 2')}}</label>                        
                                <textarea id="content2" name="content2" class="form-control">{{ $JointData[0]->content2 }}</textarea>
                                @error('content2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <?php if($title && $title == 'Edit'){ ?>
                                    <label for="photo2">{{__('Section Image 2')}}</label>
                                    <input type="file" name="photo2" id="photo2" class="form-control">
                                <?php } ?>                                 
                                @if(!empty($JointData[0]->photo2))
                                    <label for="photo2">{{ __('Photo 2') }}</label>
                                    <div class="mt-3">
                                        <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo2 }}" alt="Photo 1" class="img-fluid" style="max-width: 20%!important;">
                                    </div>
                                @endif
                                <hr>
                                <h4 class="mt-4">{{ __('Section 3') }}</h4>
                                <label for="heading3">{{__('Section Heading 3')}}</label>
                                <input type="text" class="form-control @error('heading3') is-invalid @enderror" id="heading3" name="heading3" value="{{ old('heading3', $JointData[0]->heading3) }}" {{ $disabled }}  required>
                                @error('heading3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="content3">{{__('Section Description 3')}}</label>                        
                                <textarea id="content3" name="content3" class="form-control">{{ $JointData[0]->content3 }}</textarea>
                                @error('content3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <?php if($title && $title == 'Edit'){ ?>
                                    <label for="photo3">{{__('Section Image 3')}}</label>
                                    <input type="file" name="photo3" id="photo3" class="form-control">
                                <?php } ?>  
                                @if(!empty($JointData[0]->photo3))
                                    <label for="photo3">{{ __('Photo 3') }}</label>
                                    <div class="mt-3">
                                        <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo3 }}" alt="Photo 1" class="img-fluid" style="max-width: 20%!important;">
                                    </div>
                                @endif
                                <hr>
                                <h4 class="mt-4">{{ __('Section 4') }}</h4>


                                <label for="heading4">{{__('Section Heading 4')}}</label>
                                <input type="text" class="form-control @error('4') is-invalid @enderror" id="heading4" name="heading4" value="{{ old('heading4', $JointData[0]->heading4) }}" {{ $disabled }}  required>
                                @error('heading4')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror                      

                                

                                <label for="content4">{{__('Section Description 4')}}</label>                        
                                <textarea id="content4" name="content4" class="form-control">{{ $JointData[0]->content4 }}</textarea>
                                @error('content1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <?php if($title && $title == 'Edit'){ ?>
                                    <label for="photo4">{{__('Section Image 4')}}</label>
                                    <input type="file" name="photo4" id="photo4" class="form-control">
                                <?php } ?>
                                @if(!empty($JointData[0]->photo4))
                                    <label for="photo4">{{ __('Photo 4') }}</label>
                                    <div class="mt-3">
                                        <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo4 }}" alt="Photo 1" class="img-fluid" style="max-width: 20%!important;">
                                    </div>
                                @endif
                                <hr>
                                <h4 class="mt-4">{{ __('Section 5') }}</h4>
                                <label for="content5">{{__('Section Description 5')}}</label>                        
                                <textarea id="content5" name="content5" class="form-control">{{ $JointData[0]->content5 }}</textarea>
                                @error('content1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <?php if($title && $title == 'Edit'){ ?>
                                    <label for="photo5">{{__('Section Image 5')}}</label>
                                    <input type="file" name="photo5" id="photo5" class="form-control">
                                <?php } ?>  
                                @if(!empty($JointData[0]->photo5))
                                    <label for="photo5">{{ __('Photo 5') }}</label>
                                    <div class="mt-3">
                                        <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo5 }}" alt="Photo 1" class="img-fluid" style="max-width: 20%!important;">
                                    </div>
                                @endif

                                <?php /*if($title && $title == 'Edit'){ ?>
                                    <label for="photo1">{{__('Section Image 1')}}</label>
                                    <input type="file" name="photo1" id="photo1" class="form-control">

                                    <label for="photo1">{{__('Section Image 2')}}</label>
                                    <input type="file" name="photo2" id="photo2" class="form-control">

                                    <label for="photo3">{{__('Section Image 3')}}</label>
                                    <input type="file" name="photo3" id="photo3" class="form-control">

                                    <label for="photo4">{{__('Section Image 4')}}</label>
                                    <input type="file" name="photo4" id="photo4" class="form-control">

                                    <label for="photo5">{{__('Section Image 5')}}</label>
                                    <input type="file" name="photo5" id="photo5" class="form-control">
                                <?php } */?>

                                <!-- <label for="password">{{__('Existing Photo')}}</label>  
                                <div class="img2 ">
                                    <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo1 }}" style="width: 20%!important;" />
                                    <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo2 }}" style="width: 20%!important;" />
                                    <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo3 }}" style="width: 20%!important;" />
                                    <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo4 }}" style="width: 20%!important;" />
                                    <img class="mb-2" src="{{ url('/public/homepage_img/') }}/{{ $JointData[0]->name }}/{{ $JointData[0]->photo5 }}" style="width: 20%!important;" />
                                </div> -->
                                
                                <br>

                                <?php if($title && $title == 'Edit'){ ?>
                                    <input type="submit" value="Save" class="btn btn-success" />
                                <?php } ?>
                                    <a href="{{ url('/joints') }}" class="btn btn-danger">Back</a>
                            </form>
                            </div>
                    </div>
            </div>
        </div>

@endsection

