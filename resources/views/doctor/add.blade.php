@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Manage Doctor'])
        <div class="row mt-4 mx-4">
            <div class="col-12">
                    <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Add Doctor</h6>
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
                                <form action="{{ URL('/create-doctor') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label for='first_name'>{{ __('First name') }}</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label for="last_name">{{__('Last name')}}</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label for="email_address">{{__('Email address')}}</label>
                                    <input type="text" class="form-control @error('email_address') is-invalid @enderror" id="email_address" name="email_address" placeholder="Email Address" value="{{ old('email_address') }}" required>
                                    @error('email_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label for="phone_number">{{__('Phone number')}}</label>
                                    <input type="text" class="form-control format_phone @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label for="state">{{__('Select State')}}</label>
                                    <select id="state" name="state" class="form-select form-control @error('state') is-invalid @enderror" required>
                                        <option value="">Select a State</option>
                                    </select>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror                   

                                    <label for="city">{{__('Select City')}}</label>
                                    <select id="city" name="city" class="form-select form-control @error('city') is-invalid @enderror" required>
                                        <option value="">Select a City</option>
                                    </select>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror                   

                  
                                    <label for="password">{{__('Password')}}</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{ old('password') }}" required>                                    
                                    <label for="experience">{{__('Experience')}}</label>
                                    <input type='text' class='form-control' id='experience' name='experience' placeholder="Experience" value="{{ old('experience') }}" >

                                    <label for="degree">{{__('Degree')}}</label>
                                    <input type='text' class='form-control' id='degree' name='degree' placeholder="Degree" value="{{ old('degree') }}" >

                                    <label for="speciality">{{__('Speciality')}}</label>
                                    <input type='text' class='form-control' id='speciality' name='speciality' placeholder="Speciality" value="{{ old('speciality') }}">

                                    <label for="about">{{__('About')}}</label>
                                    <textarea class='form-control' id='about' name='about' placeholder="About" value="{{ old('about') }}" ></textarea>

                                    <label for="profile_photo">{{__('Upload Photo')}}</label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                                    
                                    <label for="admin">{{__('Is Admin?')}}</label>
                                    <input type='checkbox' class="m-3" id='admin' name='admin' value="1">

                                    <br>
                                    <input type="submit" value="Save" class="btn btn-success" />
                                    <a href="{{ url('/admin_doctors') }}" class="btn btn-danger">Back</a>
                                </form>
                                    
                            </div>
                    </div>
            </div>
        </div>

<script>        
    $(document).ready(function() 
    {

        if(document.getElementsByClassName('format_phone').length > 0) {
            document.querySelector('.format_phone').addEventListener('input', function (e) {
                var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });
        }
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

