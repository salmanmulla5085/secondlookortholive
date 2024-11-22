<!-- resources/views/admin/testimonials/edit.blade.php -->
@extends('layouts.app')

@section('content')
@include('layouts.navbars.topnav', ['title' => 'Edit Testimonial'])
<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Edit Testimonial</h6>
            </div>

            <div class="card-body px-5 pt-0 pb-2">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif


            
                <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content', $testimonial->content) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="photo">Photo</label>
                        @if($testimonial->photo)
                            <div class="mb-2">
                                <img src="{{ URL('/') }}/public/{{ $testimonial->photo }}" 
                                width="100" alt="Current Photo">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>

                    
                    <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="active" {{ $testimonial->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $testimonial->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    </div>    

                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
