@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

<style>
    .error{
      color: red !important;
    }

    .toggle-old-password {
        position: absolute;
        right: 10px;
        top: 50px;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50px;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .toggle-con-password {
        position: absolute;
        right: 10px;
        top: 50px;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>

<div class="box-main p-3 bg-white margin-15-b radius8">
  <h4 class="text-center mt-3 text-dark">Update Password</h4>
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
    
    @if (session('error'))
      <div class="alert alert-danger">
      {{ session('error') }}
      </div>
    @endif
  </div> 
  <div class="row justify-content-center mt-4">
    <div class="col-12 col-lg-5 col-md-7">
      <form  id="ResetPassword" action="{{ url('/') }}/ResetPassword" method="POST">
        @csrf
        <div class="mb-4 position-relative password-icon"><label>Old Password</label>
          <input type="password" class="form-control border-radius-0" id="old_password" name="old_password" placeholder="Old Password"><i class="fa-solid fa-eye field-icon toggle-old-password"></i>
        </div>
        <div class="mb-4 position-relative password-icon"><label>New Password</label>
          <input type="password" class="form-control border-radius-0" id="password" name="password" placeholder="New Password"><i class="fa-solid fa-eye field-icon toggle-password"></i>
        </div>
        <div class="mb-4 position-relative password-icon"><label>Confirm Password</label>
          <input type="password" class="form-control border-radius-0" id="confirm_password" name="confirm_password" placeholder="Confirm Password"><i class="fa-solid fa-eye field-icon toggle-con-password"></i>
        </div>
        <button type="submit" class="btn btn-orange w-100 border-radius-0">Reset Password</button>
      </form>
    </div>
  </div>


</div>
<script>
  $(document).ready(function() {
      $.validator.addMethod("validPassword", function(value, element) {
          return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/.test(value);
      }, "Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character.");
  });
  $("#ResetPassword").validate({
    rules: {
        old_password: { 
            required: true
        },
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
        old_password: {
            required: "Please enter password",
        },
        password: {
            required: "Please enter password",
        },
        password_confirmation: "Passwords do not match"                     
    }
  });

    $('.toggle-old-password').click(function(){
        $(this).toggleClass('fa-eye fa-eye-slash');
        // var input = $($(this).prev('input'));
        var input = $('#old_password');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });

    $('.toggle-password').click(function(){
        $(this).toggleClass('fa-eye fa-eye-slash');
        // var input = $($(this).prev('input'));
        var input = $('#password');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });

    $('.toggle-con-password').click(function(){
        $(this).toggleClass('fa-eye fa-eye-slash');
        // var input = $($(this).prev('input'));
        var input = $('#confirm_password');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    }); 
</script>

</main>
</div>

<!-- Jquery -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
</body>
</html>
@endsection