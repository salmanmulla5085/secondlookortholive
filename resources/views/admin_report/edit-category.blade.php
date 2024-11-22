@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Edit Category'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Category</h6>
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

                    <form action="{{ route('admin.updateCategory', ['id' => $categoryData->id]) }}" method="POST">
                        @csrf
                        @method('POST')

                       


                    <label for='title'>{{ __('Category Name') }}</label>
                    <input type='text' class='form-control' id='category_name' name='category_name' value="{{ $categoryData->category_name }}" required>

                    

                        <br>
                        <input type="submit" value="Update" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
