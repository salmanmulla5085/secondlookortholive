@extends('layouts.app2')

@section('content')
    <style>
        .otp-input {
            width: 3.5rem;
            text-align: center;
            margin-right: 0.5rem;
            margin-left: 0.5rem;
            font-size: 1.5rem;
        }
        .otp-form {
            max-width: 400px;
            margin: 50px auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .error-form {
            max-width: 400px;
            margin: 10px auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .otp-container {
            display: flex;
            align-items: center;
        }
    </style>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.navbar')
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
                <div class="page-header min-vh-100">
                    <div class="container">
                    <div style="max-width: 400px;">
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
                </div> 
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <p class="mb-0">Enter your OTP</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('get_admin_otp') }}/{{ $user_id }}">
                                        @csrf
                                        @method('post')
                                        <div class="otp-container">
                                            <input type="text" class="form-control otp-input" name="otp1" maxlength="1" value="" required>
                                            <span>-</span>
                                            <input type="text" class="form-control otp-input" name="otp2" maxlength="1" value="" required>
                                            <span>-</span>
                                            <input type="text" class="form-control otp-input" name="otp3" maxlength="1" value="" required>
                                            <span>-</span>
                                            <input type="text" class="form-control otp-input" name="otp4" maxlength="1" value="" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Verify</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        Forgot you password? Reset your password
                                        <a href="{{ route('reset-password') }}" class="text-primary text-gradient font-weight-bold">here</a>
                                    </p>
                                </div> -->
                                <!-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Don't have an account?
                                        <a href="" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div> -->
                            </div>
                        </div>
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('<?=url("public/img/ladydoctor.jpg") ?>');
              background-size: cover;">
                                <span class="mask"></span>
                                <!-- <h4 class="mt-5 text-white font-weight-bolder position-relative">"Attention is the new
                                    currency"</h4>
                                <p class="text-white position-relative">The more effortless the writing looks, the more
                                    effort the writer actually put into the process.</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        $(document).ready(function(){
            $('.otp-input').on('input', function() {
                if ($(this).val().length === 1) {
                    $(this).nextAll('.otp-input:first').focus();
                }
            });

            $('.otp-input').on('keydown', function(e) {
                if (e.key === 'Backspace' && $(this).val() === '') {
                    $(this).prevAll('.otp-input:first').focus();
                }
            });
        });
    </script>
@endsection
