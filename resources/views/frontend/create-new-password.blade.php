@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.main') @section('main.container')
<style>
    .error{
        color: red!important;
    }
    .error-form {
        max-width: 400px;
        margin: 10px auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<main id="page-main">
    <div class="container">
        <div class="login-box">
            <div class="login-left d-none d-md-none d-lg-flex"><img src="{{ url('frontend/img/lady.png') }}" /></div>
            <div class="login-right flex-column">
                <h2 class="h2-big text-center mb-2 d-flex justify-content-center w-100">Enter New Password</h2>
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
                <div class="login-form">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="row">
                            <form id="CreateNewPassForm" action="{{ url('/') }}/create-new-password/{{ Crypt::encrypt($user_id) }}" method="POST">
                              @csrf  
                                <div class="col-12 col-md-12">
                                    <div class="mb-4"><input type="password" name="password" id="password" class="form-control" placeholder="New Password" /></div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="mb-4"><input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" /></div>
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
<script>
    $(document).ready(function() 
    {
        $.validator.addMethod("validPassword", function(value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/.test(value);
        }, "Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character.");

        $("#CreateNewPassForm").validate({
            rules: {
                password: { 
                    required: true, 
                    validPassword: true
                },
                confirm_password: { 
                    required: true, 
                    equalTo: "#password" 
                }
            },
            messages: {
                password: {
                    required: "Please enter password",
                },
                confirm_password: {
                    required: "Please enter password",
                    equalTo: "Passwords do not match"
                }                     
            },
        });
    });
</script>
@endsection
