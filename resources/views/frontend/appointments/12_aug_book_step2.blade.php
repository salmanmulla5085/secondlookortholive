@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<style>
    .btn-card:hover {
  color: #fff;
  background: #02c4b7;
}
    .btn-card {
  background: var(--orange-bg1);
  color: #fff;
  padding: 10px 20px;
  border-radius: 0;
  display: inline-flex;
  gap: 10px;
  align-items: center;
  width: fit-content;
}

.uniform-width {
        width: 100%;
        max-width: 800px; /* Adjust max-width as needed */
        margin: 0 auto;
    }
    
</style>
<main id="main-page">
<header id="content-header">
<h1 class="title-font"><img src="{{ url('/public/frontend/img/Vector(27).png') }}">Book Appointment</h1> 
<div class="d-flex gap-4 align-items-center">
  <div class="search-box d-flex justify-content-space-between align-items-center"><img src="{{ url('/public/frontend/img/Vector(29).png') }}">  <input type="text" class="form-control" placeholder="Search"><button type="button"><img src="{{ url('/public/frontend/img/Vector(29).png') }}"></button></div>
  <div class="noti"><a href=""><img src="{{ url('/public/frontend/img/Vector(31).png') }}"></a></div>
  <div class="profiles">
    <ul class="navbar-nav profile-menu"> 
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="profile-pic">
                <img src="{{ url('/public/frontend/img/Ellipse 30.png') }}" alt="Profile Picture">
             </div> <span class="d-none d-md-flex">William J.</span>
         <!-- You can also use icon as follows: -->
           <!--  <i class="fas fa-user"></i> -->
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#"><i class="fas fa-sliders-h fa-fw"></i> Account</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-cog fa-fw"></i> Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a></li>
          </ul>
        </li>
     </ul>
  </div> 
<button class="navbar-toggler btn d-flex d-md-flex d-lg-none p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-mobiles">
      <span class="navbar-toggler-icon"></span>
    </button>
</div>

</header>


<div class="box-main p-3 bg-white margin-15-b radius8">
  <!--<h4 class="text-center mt-3 text-dark">Book Appointment</h4>-->

    <div class="row mb-3 uniform-width">
        <div class="col-md-12">
            <p>Please Note: Fill all the mandatory fields.</p>
            <p>You can select a maximum of 2 connected joints.</p>
            <p>You need to submit a consent form before proceeding to pay.</p>
        </div>
    </div>


    <!-- Horizontal line -->

    <hr style="border: 1px solid gray;" class="uniform-width">


    <?php
    $sql = "SELECT * FROM dbl_users where user_type = 'doctor'";
    $users = DB::select($sql);
    $doctors = collect($users);		
    
    $sql = "SELECT * FROM tbl_plans";
    $tbl_plans = DB::select($sql);
    $tbl_plans = collect($tbl_plans);		
    
    
    ?>
        <div class="row mt-4 mx-4 px-5 mx-5">
            <div class="col-12">
                
            <div class="mb-4  px-5 mx-5">
                            <div class="">
                                <h6></h6>
                            </div>
            <div class="card-body px-5 mx-5 pt-0 pb-2">
                    
                    
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
                                    
                             </div>        
                             
            <form id="userForm" action="{{ url('/') }}/book_appointment_step2" method="POST"  enctype="multipart/form-data">
                                    @csrf
                                     
            
                    
                            
                            <div class="row mb-3">
                            <div class="col-md-4">
                            <label for="name" class="form-label">Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                            value="{{ $user->first_name }} {{ $user->last_name }}" required>
                            </div>
                            
                            <div class="col-md-4">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                            <option value="" disabled {{ $user->gender ? '' : 'selected' }}>Select your gender</option>
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            </div>
                            </div>
                            
                            <div class="row mb-3">
                            <div class="col-md-4">
                            <label for="age" class="form-label">Age<span style="color: red;">*</span></label>
                            <input type="number" class="form-control" id="age" name="age" min="0" max="99" 
                            value="{{ old('age', $user->age) }}" required>
                            </div>
                            
                            <div class="col-md-4">
                            <label for="email" class="form-label">Email Address<span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                            title="Please enter a valid email address" 
                            value="{{ old('email', $user->email_address) }}" required>
                            </div>
                            </div>
                            
                            <div class="row mb-3">
                            <div class="col-md-4">
                            <label for="contactNumber" class="form-label">Contact Number<span style="color: red;">*</span></label>
                            <input type="tel" class="form-control" id="contactNumber" name="contactNumber"
                            pattern="(\+1\s?)?(\d{3})[\s\-]?(\d{3})[\s\-]?(\d{4})"
                            title="Please enter a valid US phone number with 10 digits (e.g., 123-456-7890 or 123 456 7890)"
                            value="{{ old('contactNumber', $user->phone_number) }}" required>
                            </div>
                            
                            <div class="col-md-4">
                            <label for="alternateContactNumber" class="form-label">Alternate Contact Number</label>
                            <input type="tel" class="form-control" id="alternateContactNumber" name="alternateContactNumber"
                            pattern="(\+1\s?)?(\d{3})[\s\-]?(\d{3})[\s\-]?(\d{4})"
                            title="Please enter a valid US phone number with 10 digits (e.g., 123-456-7890 or 123 456 7890)"
                            value="{{ old('alternateContactNumber', $user->alternate_phone_number) }}">
                            </div>
                            </div>
                            
                            <div class="row mb-3">
                            <div class="col-md-4">
                            <label for="state" class="form-label">State<span style="color: red;">*</span></label>
                            <select id="state" name="state" class="form-select form-control @error('state') is-invalid @enderror" required>
                                <option value="">Select a State</option>
                            </select>
                            </div>
                            
                            <div class="col-md-4">
                            <label for="city" class="form-label">City<span style="color: red;">*</span></label>
                            <select id="city" name="city" class="form-select form-control @error('city') is-invalid @enderror" required>
                                <option value="">Select a City</option>
                            </select>
                            </div>
                            </div>


                <!-- Interests Checkboxes -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        
                        <label for="jointOfInterest" class="form-label">Joint of Interest <span style="color: red;">*</span></label>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="shoulder" name="interests[]" value="Shoulder">
                                    <label class="form-check-label" for="shoulder">
                                        Shoulder
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="knee" name="interests[]" value="Knee">
                                    <label class="form-check-label" for="knee">
                                        Knee
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="ankle" name="interests[]" value="Ankle">
                                    <label class="form-check-label" for="ankle">
                                        Ankle
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="hand" name="interests[]" value="Hand">
                                    <label class="form-check-label" for="hand">
                                        Hand
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="elbow" name="interests[]" value="Elbow">
                                    <label class="form-check-label" for="elbow">
                                        Elbow
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="back" name="interests[]" value="Back">
                                    <label class="form-check-label" for="back">
                                        Back
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="foot" name="interests[]" value="Foot">
                                    <label class="form-check-label" for="foot">
                                        Foot
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="wrist" name="interests[]" value="Wrist">
                                    <label class="form-check-label" for="wrist">
                                        Wrist
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="hip" name="interests[]" value="Hip">
                                    <label class="form-check-label" for="hip">
                                        Hip
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input joints" type="checkbox" id="neck" name="interests[]" value="Neck">
                                    <label class="form-check-label" for="neck">
                                        Neck
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- Select Appointment Type -->
                <div class="row mb-3">
                    <div class="col-md-4">                            
                        <label for="appointmentType" class="form-label">Select Appointment Type<span style="color: red;">*</span></label>
                        <select class="form-select" id="appointmentType" name="appointmentType" required>
                            <option value="" disabled selected>Select Appointment Type</option>                                                        
                            @foreach($tbl_plans as $k => $v)
                                <option 
                                    data-amount="{{ $v->plan_amount }}"  
                                    value="{{ $v->plan_type }}"
                                    @if(isset($selected_plan->plan_type) && $v->plan_type == $selected_plan->plan_type) selected @endif
                                >
                                    {{ $v->plan_type }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <!-- Upload Medical Documents -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="medicalDocuments" class="form-label">Upload Medical Documents</label>
                        <input type="file" class="form-control" id="medicalDocuments" name="medicalDocuments[]" multiple>
                    </div>
                </div>

                <!-- Previously Uploaded Files -->
                <div class="row mb-3" id="uploadedFiles" style="display:none;">
                        <!-- This section will be dynamically populated with uploaded files -->
                        <div class="col-md-12">
                        <h6>Previously Uploaded Files:</h6>
                        <ul class="list-unstyled">
                        <!-- Example file list, you will need to generate this dynamically -->
                        <!-- Example of a file item -->
                        <li class="d-flex align-items-center mb-2">
                        <button type="button" class="btn btn-danger btn-sm me-2" onclick="deleteFile('file1.pdf')">×</button>
                        <span>file1.pdf</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                        <button type="button" class="btn btn-danger btn-sm me-2" onclick="deleteFile('file2.jpg')">×</button>
                        <span>file2.jpg</span>
                        </li>
                        </ul>
                        </div>
                </div>
                
                <hr style="border: 1px solid gray;" class="uniform-width">
                                <!-- Sub Total -->
                <div class="row mb-3">
                    <div class="col-md-6 text-start">
                        <label class="form-label">Sub Total</label>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="form-label mb-0" id="total_amount">
                        @if(isset($selected_plan->plan_amount))    
                        {{ $selected_plan->plan_amount }}
                        @endif
                    </p>
                    </div>
                </div>
                
                <hr style="border: 1px solid gray;" class="uniform-width">
                
                <!-- Checkbox for agreement -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                            <span style="color: red;">*</span>
                            <label class="form-check-label" for="agree">
                                I hereby agree and declare that the information filled is correct
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="btnSubmit" id="submit" class="btn btn-card w-100 text-center justify-content-center">Proceed To Pay</button>
                  
                </form>
                                    
                            </div>
                    </div>
            </div>
        </div>
        
    <script>
    $(document).ready(function()
    {
        $("#appointmentType").change(function(){
            var selected_option = $("#appointmentType option:selected");
            var selected_amount = selected_option.data("amount");
            console.log(selected_amount);
            $("#total_amount").html(selected_amount);
            console.log($("#total_amount").html());
        });  
        
        
        //get the checked boxes
        $('.joints').change(function(){
                if ($('.joints:checked').length >= 2) {
                    $('.joints:not(:checked)').prop('disabled', true);
                } else {
                    $('.joints').prop('disabled', false);
                }
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

      document.getElementById('userForm').addEventListener('submit', function(event) {
         
      });


    });
    
    
    
    
    
        
        // Function to delete a file
    function deleteFile(filename) {
            if (confirm('Are you sure you want to delete this file?')) {
                // Here you can make an AJAX call to delete the file from the server
                // For demonstration purposes, just remove the file item from the list
                var fileItems = document.querySelectorAll('#uploadedFiles li');
                fileItems.forEach(function(item) {
                    if (item.textContent.includes(filename)) {
                        item.remove();
                    }
                });
            }
        }      


</script>

</main>
@endsection
