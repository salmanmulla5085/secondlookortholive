@extends('frontend.layouts.main') @section('main.container')
<style>
    .js_error{
        color: red!important;
    }
</style>
<main id="page-main">
    <div class="container">
        <div class="login-box"> 
            <div class="login-left d-none d-md-none d-lg-flex"><img src="{{ url('frontend/img/lady.png') }}" /></div>
            <div class="login-right flex-column">
                <h2 class="h2-big text-center mb-2 d-flex justify-content-center w-100">Forgot Password</h2>
                <p class="text-center mb-4 d-flex justify-content-center w-100">Your email address below and we'll get you back on track.</p>
                <div class="login-form">
                    <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8">
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

                        @if (session('danger'))
                            <div class="alert alert-danger">
                                {{ session('danger') }}
                            </div>
                        @endif
                    </div>
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="row">
                            <form id="ForgotPasswordForm" action="{{ url('/') }}/forgot-password" method="POST">
                              @csrf  
                                <div class="col-12 col-md-12">
                                    <div class="mb-4"><input type="email" name="email_address" class="form-control" placeholder="Email Address" /></div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="mb-4"><button type="submit" class="btn btn-card w-100 text-center justify-content-center">Submit</button></div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
