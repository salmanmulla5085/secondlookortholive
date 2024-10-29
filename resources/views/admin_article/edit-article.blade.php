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
    @include('layouts.navbars.topnav', ['title' => 'Edit Article'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Article</h6>
                </div>
                <div class="card-body px-5 pt-0 pb-2">
                @include('layouts.navbars.topnav', ['title' => 'Add Static Page'])


                    <form action="{{ route('admin.updateArticle', ['id' => $article->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                       


                    <label for='title'>{{ __('Title') }}</label>
                    <input type='text' class='form-control' id='title' name='title' value="{{ $article->title }}" required>

                    <label for="short_description">{{__('Short Description')}}</label>
                    <textarea class='form-control' id='short_desc' name='short_desc' rows="3" required>{{ $article->short_desc }}</textarea>

                    <!-- <label for="meta_keyword">{{__('Long Description')}}</label>
                        <input type='text' class='form-control' id='long_desc' name='long_desc' value="{{ old('long_desc') }}" required> -->

                    <label for="meta_desc">{{__('Long Description')}}</label>
                    <textarea class='form-control' id='long_desc' name='long_desc' rows="3" required>{{ $article->long_desc }}</textarea>

                    <label for="image">{{__('Upload Photo')}}</label>
                    <input type="file" name="image" id="image" class="form-control">
                    @if(!empty($article->image))
                                    <label for="image">{{ __('Photo') }}</label>
                                    <div class="mt-3">
                                        <img class="mb-2" src="{{ url('/public/article_images/') }}/{{ $article->image }}" alt="Photo 1" class="img-fluid" style="max-width: 20%!important;">
                                    </div>
                                @endif
                    <label for="category_id">{{__('Select category')}}</label>
                    <select id="category_id" name="category_id" class="form-select form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">Select a category</option>
                        @foreach($categoryData as $category)
                             <option value="{{ $category->id }}" <?php if($article->category_id == $category->id) echo "selected" ?>>{{ $category->category_name }}</option>
                        @endforeach
                    </select>

                        <br>
                        <input type="submit" value="Update" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
