@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Add Static Page'])

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add Static Page</h6>
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

                    <form action="{{ URL('admin/add-static-page') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for='page_name'>{{ __('Page Name') }}</label>
                        <input type='text' class='form-control' id='page_name' name='page_name' value="{{ old('page_name') }}" required>

                        <label for="page_title">{{__('Page Title')}}</label>
                        <input type='text' class='form-control' id='page_title' name='page_title' value="{{ old('page_title') }}" required>

                        <label for="meta_keyword">{{__('Meta Keywords')}}</label>
                        <input type='text' class='form-control' id='meta_keyword' name='meta_keyword' value="{{ old('meta_keyword') }}" required>

                        <label for="meta_desc">{{__('Meta Description')}}</label>
                        <textarea class='form-control' id='meta_desc' name='meta_desc' rows="3" required>{{ old('meta_desc') }}</textarea>
                        
                        <br>
                        <input type="submit" value="Save" class="btn btn-success" />
                    </form>
                        
                </div>
            </div>
        </div>
    </div>
@endsection
