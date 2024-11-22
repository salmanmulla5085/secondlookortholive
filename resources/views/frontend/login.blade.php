@extends('frontend.layouts.main')

@section('main.container')
<?php $lastSegment = last(explode('/', url()->current())); ?>
<style type="text/css">
    .js_error{
        color: red!important;
    }
</style>
<main id="page-main">
    <div class="container">
        <div class="login-box">
            <div class="login-left d-none d-md-none d-lg-flex">
                <img src="{{ url('frontend/img/lady.png') }}">
            </div>
            <div class="login-right flex-column">
                <h2 class="h2-big text-center mb-4 d-flex justify-content-center w-100">Login as a 
                    <?php if(!empty($lastSegment) && $lastSegment == 'doctor'){ echo 'Doctor'; } else { echo 'Patient'; } ?></h2>
                <div class="login-form">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-8">
                            
                            <div class="">
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

                                    @if (session('error'))
                                          <div class="alert alert-danger">
                                              {{ session('error') }}
                                          </div>
                                      @endif

                                      @if (session('warning'))
                                        <div class="alert alert-warning">
                                            {{ session('warning') }}
                                        </div>
                                        @endif


                                   
                            </div>   

                            @php
                                $url = ($lastSegment == 'doctor') ? url('/login_doctor') : url('/login');
                            @endphp    
                             
                            <form id="LoginForm" action="{{ $url }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <div class="mb-4">
                                            <input type="email" name="email_address" value="{{ old('email_address') }}" class="form-control @error('email_address') is-invalid @enderror" placeholder="Email Address" autofocus>
                                            <!--@error('email_address')-->
                                            <!--    <div class="invalid-feedback">{{ $message }}</div>-->
                                            <!--@enderror-->
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="mb-4">
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" >
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="mb-4 d-flex justify-content-end">
                                            <a href="{{ url('/forgot-password') }}" class="text-dark">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="mb-4">
                                            <button type="submit" class="btn btn-card w-100 text-center justify-content-center">Login as a <?php if($lastSegment == 'doctor'){ echo 'Doctor'; } else { echo 'Patient'; } ?></button>
                                        </div>
                                    </div>
                                    <?php if($lastSegment != 'doctor'){ ?>
                                        <div class="col-12 col-md-12 text-center">
                                            Don’t have an account? <a href="{{ url('/register') }}">Sign Up here</a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
   
    const refreshInterval = 5 * 60 * 1000; // 5 minutes

    function refreshPage() {
        location.reload();
    }

    setTimeout(refreshPage, refreshInterval);
</script>
@endsection
