@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<style type="text/css">
    .aboutArea{
        min-height: 100px!important;
    }
    .extClose {
        pointer-events: none;
        opacity: 0.5;
        cursor: default;
    }
</style>
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
<div class="all-doctors bg-white p-3 mb-3">
    <form id="userForm" action="{{ url('/') }}/acc-update" method="POST"  enctype="multipart/form-data">
        @csrf
        <div class="book-form">
            <div class="pro-pic1">
                @if (!empty($user) && $user['user_type'] === 'patient')
                    <div class="img2"><img id="previewImage" src="{{ url('/public/patient_photos/') }}/{{ $user['profile_photo'] }}" /></div>
                @elseif(!empty($user) && $user['user_type'] === 'doctor')
                    <div class="img2"><img id="previewImage" src="{{ url('/public/doctor_photos/') }}/{{ $user['profile_photo'] }}" /></div>
                @else
                    <div class="img2"><img id="previewImage" src="{{ url('/public/img/Rectangle-117.png') }}" /></div>
                @endif
                <button type="button" class="edit-img"><input type="file" id="profilePhotoInput" name="profile_photo[]" /> <img src="{{ url('/public/frontend/img/Group 9741.png') }}" /></button>
            </div>
    
            <hr />
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3"><label>Name*</label>
                    @if (!empty($user))
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->first_name }} {{ $user->last_name }}" required></div>
                    @else 
                        <input type="text" name="name" id="name" class="form-control" value="" required></div>
                    @endif
                </div>
                @if (!empty($user['user_type'] == 'patient'))
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label>Gender*</label>
                            @if (!empty($user))
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" disabled {{ old('gender', $user->gender) ? '' : 'selected' }}>Select your gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            @else 
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" disabled>Select your gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label>Age*</label>
                            @if (!empty($user))
                                <input type="number" class="form-control" id="age" name="age" min="0" max="99" 
                                value="{{ old('age', $user->age) }}" required>
                            @else
                            <input type="number" class="form-control" id="age" name="age" min="0" max="99" 
                                value="" required>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="col-12 col-md-6">
                    <div class="mb-3"><label>Email Address*</label>
                    @if (!empty($user))
                        <input type="email" class="form-control" id="email_address" name="email_address" 
                            title="Please enter a valid email address" 
                            value="{{ old('email', $user->email_address) }}" required>
                    @else
                        <input type="email" class="form-control" id="email_address" name="email_address" 
                            title="Please enter a valid email address" 
                            value="" required>
                    @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3"><label>Contact Number*</label>
                    @if (!empty($user))
                        <input type="tel" class="form-control format_phone" id="contactNumber" name="phone_number"
                            title="Please enter a valid US phone number with 10 digits (e.g., 123-456-7890 or 123 456 7890)"
                            value="{{ old('contactNumber', $user->phone_number) }}" required> 
                    @else
                        <input type="tel" class="form-control format_phone1" id="contactNumber" name="phone_number"
                            title="Please enter a valid US phone number with 10 digits (e.g., 123-456-7890 or 123 456 7890)"
                            value="" required> 
                    @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3"><label>Alternate Contact Number</label>
                    @if (!empty($user))
                        <input type="tel" class="form-control format_phone2" id="alternateContactNumber" name="alternateContactNumber"
                            title="Please enter a valid US phone number with 10 digits (e.g., 123-456-7890 or 123 456 7890)"
                            value="{{ old('alternateContactNumber', $user->alternateContactNumber) }}">
                    @else
                        <input type="tel" class="form-control format_phone3" id="alternateContactNumber" name="alternateContactNumber"
                            title="Please enter a valid US phone number with 10 digits (e.g., 123-456-7890 or 123 456 7890)"
                            value="">
                    @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label>State*</label>
                        <select id="state" name="state" class="form-select form-control @error('state') is-invalid @enderror" required>
                            <option value="">Select a State</option>    
                        </select>
                    </div>
                </div>
    
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label>City*</label>
                        <select id="city" name="city" class="form-select form-control @error('city') is-invalid @enderror" required>
                            <option value="">Select a City</option>
                        </select>
                    </div>
                </div>
                
                @if (!empty($user['user_type'] == 'doctor'))
                    <div class="col-12 col-md-6">
                        <div class="mb-3"><label>Experience</label>
                        @if (!empty($user))
                            <input type="text" name="experience" id="experience" class="form-control" value="{{ $user->experience }}"></div>
                        @else 
                            <input type="text" name="experience" id="experience" class="form-control" value=""></div>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3"><label>Degree</label>
                        @if (!empty($user))
                            <input type="text" name="degree" id="degree" class="form-control" value="{{ $user->degree }}"></div>
                        @else 
                            <input type="text" name="degree" id="degree" class="form-control" value=""></div>
                        @endif
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="mb-3"><label>Speciality</label>
                        @if (!empty($user))
                            <input type="text" name="speciality" id="speciality" class="form-control" value="{{ $user->speciality }}"></div>
                        @else 
                            <input type="text" name="speciality" id="speciality" class="form-control" value=""></div>
                        @endif
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="mb-3"><label>About</label>
                        @if (!empty($user))
                            <textarea name="about" id="about" class="form-control aboutArea" value="{{ $user->about }}">{{ $user->about }}</textarea></div>
                        @else 
                            <textarea name="about" id="about" class="form-control aboutArea" value=""></textarea></div>
                        @endif
                    </div>
                @endif
                
                @if (!empty($user['user_type'] == 'patient'))
                <div class="col-12 col-md-12">
                    <div class="mb-3">
                        <div class="mb-3"><label>Mention Allergies (If any)</label>
                        @if (!empty($user))
                            <input type="text" class="form-control" id="allergies" name="allergies" 
                            title="Please enter your allergies" 
                            value="{{ old('email', $user->allergies) }}"/></div>
                        @else
                            <input type="text" class="form-control" id="allergies" name="allergies" 
                            title="Please enter your allergies" 
                            value=""/></div>
                        @endif
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="mb-3">
                            <div class="mb-3"><label>Mention Medical History (If any)</label>
                            @if (!empty($user))
                                <input type="text" class="form-control" id="MedicalHistory" name="MedicalHistory" 
                                title="Please enter your medical history" 
                                value="{{ old('email', $user->MedicalHistory) }}"/></div>
                            @else
                                <input type="text" class="form-control" id="MedicalHistory" name="MedicalHistory" 
                                title="Please enter your medical history" 
                                value=""/></div>
                            @endif
                        </div>
                @endif
                    </div>
                
                @if (!empty($user['user_type'] == 'patient'))
                    <div class="col-12 col-md-12">
                        <div class="mb-3"><label class="d-flex">Upload Medical Documents</label> 
                            <button class="btn btn-orange-outline d-inline-flex gap-2 align-items-center mb-3" type="button">
                            
                            <input type="file" id="medicalDocuments" name="medicalDocuments[]" multiple><img src="
                            {{ url('/public/frontend/img/Group(43).png') }}"> Upload</button>
                            
                            <div class="uploaded-files d-flex flex-column gap-2">
                                <!-- Uploaded files will be dynamically inserted here -->
                            </div>

                        </div>
                    </div>
                @endif
    
                    <div class="col-12 col-md-12">
                        <div class="mb-3 d-flex justify-content-end align-items-center gap-3">
                            <a id="resetButton" class="btn btn-outline-dark border-radius-0 ps-4 pe-4">Reset</a>
                            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function(){

    const phoneFormats = document.querySelectorAll('.format_phone, .format_phone1, .format_phone2, .format_phone3');

    phoneFormats.forEach(function (element) {
        element.addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    });

    
    $('#resetButton').click(function(){
        $('#userForm')[0].reset(); // This resets the form to its initial state
        $('#userForm input[type="text"]').val('');
        $('#userForm input[type="number"]').val('');
        $('#userForm input[type="tel"]').val('');
        $('#userForm input[type="email"]').val('');
        $('select option:selected').removeAttr('selected');
    });
    
    $.ajax({
        url: "{{ URL('/states') }}",
        method: 'GET',
        success: function(data) {
            const stateSelect = $('#state');
            const selectedState = "{{ $user->state }}"; // Get the saved state value from the server      
            stateSelect.empty();
            stateSelect.append('<option value="">Select a State</option>');
            data.forEach(state => {
                
                const isSelected = state.ID == selectedState ? 'selected' : ''; // Check if this is the selected state
                stateSelect.append(`<option value="${state.ID}" ${isSelected}>${state.STATE_NAME}</option>`);
                
            });
        }
    });
    
    var selectedState = "{{ $user->state }}"; // Get the saved state value from the server      

    // load cities if state is already loaded 
    if(selectedState && selectedState > 0)
    {
        var stateId = selectedState;
        if (stateId) {
            $.ajax({
                url: `{{ URL('/cities') }}/${stateId}`,
                method: 'GET',
                success: function(data) {
                    const citySelect = $('#city');
                    const selectedCity = "{{ $user->city }}"; // Get the saved state value from the server                          
                    citySelect.empty();
                    citySelect.append('<option value="">Select a City</option>');
                    data.forEach(city => {
                        const isSelected = city.ID == selectedCity ? 'selected' : ''; // Check if this is the selected state
                        citySelect.append(`<option value="${city.ID}" ${isSelected}>${city.CITY}</option>`);
                    });
                }
            });
        } else {
            $('#city').empty().append('<option value="">Select a City</option>');
        }    
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
                    const selectedCity = "{{ $user->city }}"; // Get the saved state value from the server                          
                    citySelect.empty();
                    citySelect.append('<option value="">Select a City</option>');
                    data.forEach(city => {
                        const isSelected = city.ID == selectedCity ? 'selected' : ''; // Check if this is the selected state
                        citySelect.append(`<option value="${city.ID}" ${isSelected}>${city.CITY}</option>`);
                    });
                }
            });
        } else {
            $('#city').empty().append('<option value="">Select a City</option>');
        }
    }
});
    const uploadedFilesContainer = document.querySelector('.uploaded-files');
    var ExtmedicalDocuments = '<?php if(!empty($user->medicalDocuments)){ echo $user->medicalDocuments; }?>';
    const serverpath = "{{ URL('/') }}/public/patient_reports/";

    if(ExtmedicalDocuments != '' && typeof ExtmedicalDocuments !== 'undefined'){
        var medicalDocumentsArray = ExtmedicalDocuments.split(",");
        var fileInput = medicalDocumentsArray;
        
        const files = fileInput;
        uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

        Array.from(files).forEach(file => {
            const fileName = file;

            // Create a new file item
            const fileItem = document.createElement('div');
                fileItem.className = 'd-flex gap-2 align-items-center';
                
                const closeButton = document.createElement('a');
                closeButton.href = '#';
                closeButton.className = 'close';
                closeButton.innerHTML = `<img class="existing_files" data-filename="${fileName}" src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;

                const fileLink = document.createElement('a');
                fileLink.href = `${serverpath}${fileName}`;
                fileLink.textContent = fileName;
                fileLink.target = "_blank";  

                fileItem.appendChild(closeButton);
                fileItem.appendChild(fileLink);
                uploadedFilesContainer.appendChild(fileItem);

                closeButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    const filename = this.querySelector('img').getAttribute('data-filename');
                    const userId = '<?php echo $user['id'];?>'; 

                    // Perform AJAX request to delete the file
                    fetch('{{ route("delete_user.file") }}', {
                    method: 'POST', 
                    headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                    userId: userId,
                    filename: filename
                    })
                    })
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                      alert('File deleted successfully.');
                      fileItem.remove();
                    } else {
                    alert('Failed to delete file.');
                    }
                    })
                    .catch(error => {
                    alert('An error occurred while deleting the file.');
                    });

                });
        });
    }

        var PushedImg = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            var fileInput = document.getElementById('medicalDocuments');
            const uploadedFilesContainer = document.querySelector('.uploaded-files');
            
            fileInput.addEventListener('change', function() {
            const files = fileInput.files;
            // uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

            Array.from(files).forEach(file => {

                if (file.size > 4096 * 1024) {
                    alert('The image/file size must not be greater than 5Mb');
                    return false;
                }

                PushedImg.push(file);

                var dataTransfer = new DataTransfer();

                if(PushedImg != ''){
                    for (var i = 0; i < PushedImg.length; i++) {
                        dataTransfer.items.add(PushedImg[i]);
                    }

                    // Set the files to the input field
                    $('#medicalDocuments')[0].files = dataTransfer.files;
                }

                const fileName = file.name;

                // Create a new file item
                const fileItem = document.createElement('div');
                fileItem.className = 'd-flex gap-2 align-items-center';
                
                const closeButton = document.createElement('a');
                closeButton.href = '#';
                closeButton.className = 'close';
                closeButton.innerHTML = `<img src="{{ url('/public/frontend/img/CLose(1).png') }}" alt="Close">`;
                closeButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    // Remove the file from the list
                    fileItem.remove();
                    // Optionally, you can also handle the file deselection from the input element
                    // by re-updating the file input value, but this may require more complex handling
                });

                const fileLink = document.createElement('a');
                fileLink.href = '#';
                fileLink.textContent = fileName;

                fileItem.appendChild(closeButton);
                fileItem.appendChild(fileLink);
                uploadedFilesContainer.appendChild(fileItem);
            });
        });
    });

    $(document).ready(function(){
        $('#profilePhotoInput').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });
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