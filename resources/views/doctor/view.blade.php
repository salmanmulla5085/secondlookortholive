<?php if($title == 'view'){ 
    $title = 'View';
    $disabled = 'disabled';
} elseif($title == 'edit') { 
    $title = 'Edit';
    $disabled = '';
}?>
@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Manage Doctor'])
        <div class="">
           
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
        </div>
        <div class="row mt-4 mx-4">
            <div class="col-12">
                    <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>{{ $title }} Doctor</h6>
                            </div>
                            <div class="card-body px-5 pt-0 pb-2">
                            <form action="{{ URL('/create-doctor') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="ExtDoctorId" name="ExtDoctorId" value="{{ $UserData[0]->id }}">
                                <label for="first_name">{{ __('First name') }}</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $UserData[0]->first_name) }}" {{ $disabled }} required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="last_name">{{__('Last name')}}</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $UserData[0]->last_name) }}" {{ $disabled }}  required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="email_address">{{__('Email address')}}</label>
                                <input type="text" class="form-control @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address', $UserData[0]->email_address) }}" {{ $disabled }} required>
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="phone_number">{{__('Phone number')}}</label>
                                <input type="text" class="form-control format_phone @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $UserData[0]->phone_number) }}" {{ $disabled }} required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="state">{{__('Select State')}}</label>
                                <select id="state" name="state" class="form-select form-control @error('state') is-invalid @enderror" {{ $disabled }} required>
                                    <option value="">Select a State</option>
                                </select>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror                   

                                <label for="city">{{__('Select City')}}</label>
                                <select id="city" name="city" class="form-select form-control @error('city') is-invalid @enderror" {{ $disabled }} required>
                                    <option value="">Select a City</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror                   

              
                                <!-- <label for="password">{{__('Password')}}</label>
                                <input type='password' class='form-control' id='password' name='password' value="{{ old('password', $UserData[0]->view_password) }}" {{ $disabled }} > -->

                                <label for="experience">{{__('Experience')}}</label>
                                <input type='text' class='form-control' id='experience' name='experience' value="{{ old('experience', $UserData[0]->experience) }}" {{ $disabled }} >

                                <label for="degree">{{__('Degree')}}</label>
                                <input type='text' class='form-control' id='degree' name='degree' value="{{ old('degree', $UserData[0]->degree) }}" {{ $disabled }} >

                                <label for="speciality">{{__('Speciality')}}</label>
                                <input type='text' class='form-control' id='speciality' name='speciality' value="{{ old('speciality', $UserData[0]->speciality) }}" {{ $disabled }} >

                                <label for="about">{{__('About')}}</label>
                                <textarea class='form-control' id='about' name='about' value="{{ old('about', $UserData[0]->about) }}" {{ $disabled }} >{{ $UserData[0]->about }}</textarea>
                               
                                <label for="admin">{{__('Is Admin?')}}</label>
                                    <input type='checkbox' class="m-3" id='admin' name='admin' {{ $UserData[0]->admin ? "checked" : ''}}><br>

                                <?php if($title && $title == 'Edit'){ ?>
                                <label for="profile_photo">{{__('Upload Photo')}}</label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                                <?php } ?>

                                <label for="password">{{__('Existing Photo')}}</label>  
                                <div class="img2"><img src="{{ url('/public/doctor_photos/') }}/{{ $UserData[0]->profile_photo }}" style="width: 20%!important;" /></div>
                                
                                <br>

                                <?php if($title && $title == 'Edit'){ ?>
                                    <input type="submit" value="Save" class="btn btn-success" />
                                <?php } ?>
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
              stateSelect.append('<option value="">Select a State</option>');
              const selectedState = "{{ $UserData[0]->state }}";
              stateSelect.empty();
              data.forEach(state => {
                  const isSelected = state.ID == selectedState ? 'selected' : '';
                  stateSelect.append(`<option value="${state.ID}" ${isSelected}>${state.STATE_NAME}</option>`);
              });
          }
        });

        var SelectCity = '<?php echo $UserData[0]->state; ?>';
            
        if(SelectCity != ''){
            ChangeCity(SelectCity);
        }

        // Fetch cities based on selected state
        $('#state').change(function() {
            const stateId = $(this).val();
            ChangeCity(stateId);
        });  

        function ChangeCity(stateId){
            if (stateId) {
              $.ajax({
                  url: `{{ URL('/cities') }}/${stateId}`,
                  method: 'GET',
                  success: function(data) {
                      const citySelect = $('#city');
                      citySelect.empty();
                      const selectedCity = "{{ $UserData[0]->city }}";
                      citySelect.append('<option value="">Select a City</option>');
                      data.forEach(city => {
                        const isSelectedCity = city.ID == selectedCity ? 'selected' : '';

                          citySelect.append(`<option value="${city.ID}" ${isSelectedCity}>${city.CITY}</option>`);
                      });
                  }
              });
          } else {
              $('#city').empty().append('<option value="">Select a City</option>');
          }
        }  

    });
      
</script>

@endsection

