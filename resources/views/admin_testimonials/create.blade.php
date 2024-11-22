<!-- resources/views/admin/testimonials/create.blade.php -->
@extends('layouts.app')

@section('content')
@include('layouts.navbars.topnav', ['title' => 'Add Testimonial'])
<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Add Testimonial</h6>
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


                <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>

                    <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    </div>    


                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
