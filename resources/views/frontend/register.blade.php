@extends('frontend.layouts.main')

@section('main.container')

<style>
  #first_name-error, #last_name-error, #email_address-error, #phone_number-error, #password-error, #password_confirmation-error, #state-error, #city-error{
    color: red !important;
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
            <div class="login-form">
   
              
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

            
            <form id="registrationForm" action="{{ url('/') }}/register" method="POST">
                @csrf
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="mb-4">
                      <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                      @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                      
                    <div class="mb-4">
                      <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                      @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="mb-4">
                      <input type="email" class="form-control @error('email_address') is-invalid @enderror" name="email_address" placeholder="Email Address" value="{{ old('email_address') }}">
                      @error('email_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="mb-4">
                      <input type="tel" class="form-control format_phone @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                      @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="mb-4">
                      <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="mb-4">
                      <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">
                      @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="mb-4">                      
                      <select id="state" name="state" class="form-select form-control @error('state') is-invalid @enderror" required>
                          <option value="">Select a State</option>
                      </select>
                      @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="mb-4">                      
                      <select id="city" name="city" class="form-select form-control @error('city') is-invalid @enderror" required>
                          <option value="">Select a City</option>
                      </select>
                      @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12 col-md-12">
                    <div class="mb-4">
                      <button type="submit" class="btn btn-card w-100 text-center justify-content-center">Register as a Patient</button>
                    </div>
                  </div>
                  <div class="col-12 col-md-12">
                    <div class="mb-4 text-center">
                      By proceeding, I agree that "SecondLookOrtho" or its representatives may contact me by email, phone, or SMS (including by automated means) at the email address or number I provide. I have read and understood the Terms & Conditions.
                    </div>
                  </div>
                  <div class="col-12 col-md-12 text-center">
                    Already a registered patient? <a href="{{ url('/login') }}">Sign in here</a>
                  </div>
                </div>
              </form>
            </div>
      </div>
    </div>
  </div>
</main>


<script>

if (typeof jQuery !== 'undefined') {
    console.log('jQuery is loaded!');
} else {
    console.log('jQuery is not loaded.');
}

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('registrationForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault(); // Prevent form submission
                event.stopPropagation(); // Stop event propagation
                form.reportValidity(); // Show validation errors
            }
        });
    });
    
    $(document).ready(function() {
        $.validator.addMethod("validPassword", function(value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/.test(value);
        }, "Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character.");
    });
    
    $(document).ready(function() 
    {

      if(document.getElementsByClassName('format_phone').length > 0) {
        document.querySelector('.format_phone').addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    }
    
      $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[A-Za-z\s'-]+$/.test(value);
    }, "Please use letters only (no numbers or special characters).");

    // Custom validator for regex rule
    $.validator.addMethod("regex_email", function(value, element, regexpr) {          
        return regexpr.test(value);
    }, "Invalid format.");

$("#registrationForm").validate({
    rules: {
        first_name: { 
            required: true, 
            minlength: 2, 
            lettersOnly: true 
        },
        last_name: { 
            required: true, 
            minlength: 2, 
            lettersOnly: true 
        },
        email_address: { 
            required: true, 
            email: true,
            regex_email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/ // Custom regex rule for email 
        },
        phone_number: { 
            required: true
        },
        password: { 
            required: true, 
            validPassword: true
        },
        password_confirmation: { 
            required: true, 
            equalTo: "#password" 
        },
        state: { 
            required: true 
        },
        city: { 
            required: true 
        }
    },
    messages: {
        first_name: {
            required: "Please enter your first name",
            lettersOnly: "First name should not contain numbers or special characters"
        },
        last_name: {
            required: "Please enter your last name",
            lettersOnly: "Last name should not contain numbers or special characters"
        },
        password: {
            required: "Please enter password",
        },
        email_address: {
            required: "Please enter email",
            email: "Please enter a valid email address",
            regex_email: "Please enter a valid email address"
        },
        phone_number: "Please enter a valid phone number in the format (XXX) XXX-XXXX or XXX-XXX-XXXX.",
        password_confirmation: "Passwords do not match",
        state: "Please select a state",
        city: "Please select a city"                        
    },
    // errorPlacement: function(error, element) {
    //     error.addClass('invalid-feedback');
    //     element.closest('.form-group').append(error);
    // },
    // highlight: function(element) {
    //     $(element).addClass('is-invalid').removeClass('is-valid');
    // },
    // unhighlight: function(element) {
    //     $(element).removeClass('is-invalid').addClass('is-valid');
    // }
});




      // Fetch states on page load
      $.ajax({
          url: "{{ URL('/states') }}",
          method: 'GET',
          success: function(data) {
              const stateSelect = $('#state');
              stateSelect.empty();
              stateSelect.append('<option value="">Select a State</option>');
              data.forEach(state => {
                  stateSelect.append(`<option value="${state.ID}">${state.STATE_NAME}</option>`);
              });
          }
      });

      // Fetch cities based on selected state
      $('#state').change(function() {
          const stateId = $(this).val();
          if (stateId) {
              $.ajax({
                  url: `{{ URL('/cities') }}/${stateId}`,
                  method: 'GET',
                  success: function(data) {
                      const citySelect = $('#city');
                      citySelect.empty();
                      citySelect.append('<option value="">Select a City</option>');
                      data.forEach(city => {
                          citySelect.append(`<option value="${city.ID}">${city.CITY}</option>`);
                      });
                  }
              });
          } else {
              $('#city').empty().append('<option value="">Select a City</option>');
          }
      });    

    });
      
</script>

@endsection