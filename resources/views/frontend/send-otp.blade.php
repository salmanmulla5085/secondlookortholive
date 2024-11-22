@extends('frontend.layouts.main')

@section('main.container')

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
    
<main id="page-main">
  <div class="container">
    <div class="login-box">
      <div class="login-left d-none d-md-none d-lg-flex">
        <img src="{{ url('frontend/img/lady.png') }}">
      </div>
      <div class="login-right flex-column">
        <h2 class="h2-big text-center mb-4 d-flex justify-content-center w-100">Register as a Patient</h2>
        <?php
        $lastFiveDigits = substr($user_row['phone_number'], -5);
        ?>
        <h2 class="h2-big text-center mb-4 d-flex justify-content-center w-100">Enter verification code sent on: <br><?= '*****' . $lastFiveDigits ?></h2>
        <div class="error-form">
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
         
         
         <div class="login-form otp-form">
            <form  id="otpForm" action="{{ url('/') }}/send-otp/{{ base64_encode($user_row['id']) }}" method="POST">
            @csrf
                <div class="otp-container">
                    <input type="text" class="form-control otp-input" name="otp1" maxlength="1" value="" required>
                    <span>-</span>
                    <input type="text" class="form-control otp-input" name="otp2" maxlength="1" value="" required>
                    <span>-</span>
                    <input type="text" class="form-control otp-input" name="otp3" maxlength="1" value="" required>
                    <span>-</span>
                    <input type="text" class="form-control otp-input" name="otp4" maxlength="1" value="" required>
                </div>
                <div class="d-grid mt-4">
                   <button type="submit" class="btn btn-card w-100 text-center justify-content-center">Verify</button>
                </div>
                <div class="row mt-2"><p>Didn't received Code? <a href="{{ url('/')}}/resend_otp/{{ base64_encode($user_row['id']) }}">Resend</a></div>
            </form>
        </div>
      </div>
    </div>
  </div>
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

        $('.otp-input').on('keypress', function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
    });
  </script>
</main>

@endsection
