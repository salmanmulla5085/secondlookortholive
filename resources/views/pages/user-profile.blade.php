@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Your Profile'])
    <div class="card shadow-lg mx-4">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative d-none">
                        <img src="{{ url('/') }}/public/img/team-2.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                        {{ $admin['firstname'] ?? 'Firstname' }} {{ $admin['lastname'] ?? 'Lastname' }}

                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            Admin
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <!-- <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">App</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-email-83"></i>
                                    <span class="ms-2">Messages</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span class="ms-2">Settings</span>
                                </a>
                            </li>
                        </ul> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alert">
            @include('components.alert')
                </div>
                <div class="container-fluid py-4 ">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="username" value="{{ old('username', $admin['username']) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email', $admin['email']) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">First name</label>
                                        <input class="form-control" type="text" name="firstname"  value="{{ old('firstname', $admin['firstname']) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Last name</label>
                                        <input class="form-control" type="text" name="lastname" value="{{ old('lastname', $admin['lastname']) }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Contact Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ old('address', $admin['address']) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Mobile No.</label>
                                        <input class="form-control" type="text" name="phone_number"
                                            value="{{ old('phone_number', $admin['phone_number']) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">City</label>
                                        <input class="form-control" type="text" name="city" value="{{ old('city', $admin['city']) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Country</label>
                                        <input class="form-control" type="text" name="country" value="{{ old('country', $admin['country']) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Postal code</label>
                                        <input class="form-control" type="text" name="postal" value="{{ old('postal', $admin['postal']) }}">
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">About me</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">About me</label>
                                        <input class="form-control" type="text" name="about"
                                            value="{{ old('about', $admin['about']) }}">
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8 mt-4">
    <div class="card">
        <form role="form" method="POST" action="{{ route('changepassword') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <p class="mb-0">Change Password</p>
                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 col-lg-12">
                        <div class="form-group">
                            <label for="old-password" class="form-control-label">Old Password</label>
                            <input class="form-control" type="password" name="old_password" id="old-password" required>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12">
                        <div class="form-group">
                            <label for="new-password" class="form-control-label">New Password</label>
                            <input class="form-control" type="password" name="new_password" id="new-password" required>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12">
                        <div class="form-group">
                            <label for="confirm-password" class="form-control-label">Confirm Password</label>
                            <input class="form-control" type="password" name="cnf_new_password" id="confirm-password" required>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

            
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
