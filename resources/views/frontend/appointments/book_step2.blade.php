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

.extClose {
    pointer-events: none;
    opacity: 0.5;
    cursor: default;
}

.uniform-width {
        width: 100%;
        max-width: 800px; /* Adjust max-width as needed */
        margin: 0 auto;
    }
    
</style>

    <?php
        $sql = "SELECT * FROM dbl_users where user_type = 'doctor'";
        $users = DB::select($sql);
        $doctors = collect($users);		
        
        $sql = "SELECT * FROM tbl_plans";
        $tbl_plans = DB::select($sql);
        $tbl_plans = collect($tbl_plans);		
        
        $SessionappointmentType = session('appointmentType', []); 
    ?>


    <div class="all-doctors bg-white p-3 mb-3">
        <div class="book-form">
                <div class="d-flex gap-2 text-muted">
                <div class="">Please Note :</div>
                <div class="d-flex flex-column gap-1 "><span>Fill all the mandatory fields</span><span>You can select maximum of 2 concerned joints</span><span>You need to submit consent form before proceeding to pay</span></div>
                </div>
                <hr/>

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
                <div class="row">
                        <div class="col-12 col-md-6"><div class="mb-3"><label>Name*</label>
                        <input type="text" name="name"  id="name"  class="form-control" value="{{ $user->first_name }} {{ $user->last_name }}" required>

                        </div></div>
                        <div class="col-12 col-md-6"><div class="mb-3"><label>Gender*</label>  
                        @if(!empty($ExtAppData->gender))
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled {{ old('gender', $ExtAppData->gender) ? '' : 'selected' }}>Select your gender</option>
                                <option value="male" {{ old('gender', $ExtAppData->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $ExtAppData->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $ExtAppData->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        @else
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled {{ old('gender', $user->gender) ? '' : 'selected' }}>Select your gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        @endif

                        </div></div>
                        <div class="col-12 col-md-6"><div class="mb-3"><label>Age*</label>
                        
                        @if(!empty($ExtAppData->age))
                            <input type="number" class="form-control" id="age" name="age" min="0" max="99" 
                        value="{{ old('age', $ExtAppData->age) }}" required>
                        @else
                            <input type="number" class="form-control" id="age" name="age" min="0" max="99" 
                        value="{{ old('age', $user->age) }}" required>
                        @endif

                        </div></div>
                        <div class="col-12 col-md-6"><div class="mb-3"><label>Email Address*</label>
                        <input type="email" class="form-control" id="email" name="email" 
                        title="Please enter a valid email address" 
                        value="{{ old('email', $user->email_address) }}" required>

                        </div></div>
                        <div class="col-12 col-md-6"><div class="mb-3"><label>Contact Number*</label>
                        <input type="tel" class="form-control format_phone" id="contactNumber" name="contactNumber"
                        title="Please enter a valid US phone number with 10 digits (e.g.(123) 456-7890)"
                        value="{{ old('contactNumber', $user->phone_number) }}" required>  
                        @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div></div>
                        <div class="col-12 col-md-6"><div class="mb-3"><label>Alternate Contact Number</label>
                        <input type="tel" class="form-control alt_format_phone" id="alternateContactNumber" name="alternateContactNumber"
                        title="Please enter a valid US phone number with 10 digits (e.g.(123) 456-7890)"
                        value="{{ old('alternateContactNumber', $user->alternateContactNumber) }}">
                        </div></div>

                        <div class="col-12 col-md-6"><div class="mb-3"><label>State*</label>
                        <select id="state" name="state" class="form-select form-control @error('state') is-invalid @enderror" required>
                        <option value="">Select a State</option>    
                        </select>
                        </div></div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3"><label>City*</label>
                                <select id="city" name="city" class="form-select form-control @error('city') is-invalid @enderror" required>
                                <option value="">Select a City</option>
                                </select>

                            </div>
                        </div>

                        

                        <div class="col-12 col-md-12">
                            <div class="mb-1"><label class="d-flex flex-column gap-1">Joint of Interest*<small class="t-orange" >Note: You can select up to 2 joints</small></label> 
                            </div>

                          <!-- Interests Checkboxes -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="jointOfInterest" class="form-label">Joint of Interest <span style="color: red;">*</span></label>
                                        <div class="row" id="checkbox-container">
                                        <!-- Checkboxes will be injected here -->
                                        </div>
                                </div>
                            </div>                        

                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3"><label>Symptoms</label>
                                @if(!empty($ExtAppData->symptoms))
                                    <input type="text" name="symptoms" id="symptoms" class="form-control" value="{{ $ExtAppData->symptoms }}">
                                @else
                                    <input type="text" name="symptoms" id="symptoms" class="form-control" value="{{ old('symptoms', $user->symptoms) }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3"><label>Category*</label>  
                                @if(!empty($ExtAppData->category))
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" disabled {{ old('category', $ExtAppData->category) ? '' : 'selected' }}>Select Category</option>
                                        <?php if($appointmentType == 'Report Review'){?>
                                            <option value="New Report Review" {{ old('category', $ExtAppData->category) == 'New Report Review' ? 'selected' : '' }}>New Report Review</option>
                                        <?php } else { ?>
                                            <?php if($cat_type == ''){?>
                                                <option value="New Appointment" {{ old('category', $ExtAppData->category) == 'New Appointment' ? 'selected' : '' }}>New Appointment</option>
                                            <?php } else {?>
                                                <option value="Follow Up" {{ old('category', $ExtAppData->category) == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                @else
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" disabled>Select Category</option>
                                        <?php if($appointmentType == 'Report Review'){?>
                                            <option value="New Report Review" selected>New Report Review</option>
                                        <?php } else { ?>
                                            <?php if($cat_type == ''){?>
                                                <option value="New Appointment" selected>New Appointment</option>
                                            <?php } else {?>
                                                <option value="Follow Up" {{ old('category', $user->category) == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" id="app_id" name="app_id" value="<?php if($app_id && !empty($app_id)){ echo $app_id; } ?>">                        
                        
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
                        

                        <div class="col-12 col-md-6"><div class="mb-3"><label class="mb-0 d-flex align-items-center gap-2">
                        <input type="checkbox" id="agree" name="agree" required> I here by declare text message*</label></div></div>

                        <div class="col-12 col-md-12"><div class="mb-3">
                            <button type="submit" name="btnSubmit" id="submit" class="btn btn-orange border-radius-0 pt-3 pb-3 ps-5 pe-5">
                                <?php if($cat_type != '') { echo 'Submit'; } else { if($app_id && !empty($app_id)){ echo 'Reschedule'; } else { echo 'Proceed to Pay'; } } ?></button>
                        </div></div>



                </div>

                </form>

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

            if(document.getElementsByClassName('alt_format_phone').length > 0) {
                document.querySelector('.alt_format_phone').addEventListener('input', function (e) {
                    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                    e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
                });
            }
            
            var SelectCity = '<?php echo $user->city; ?>';
            
            if(SelectCity != ''){
                ChangeCity(SelectCity);
            }            
            
            //get the checked boxes
            $('.joints').change(function()
            {
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
            
        // Function to delete a file
        function deleteFile(filename) 
        {
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

        

        const joints = [
                { id: 'shoulder', value: 'Shoulder', label: 'Shoulder' },
                { id: 'knee', value: 'Knee', label: 'Knee' },
                { id: 'ankle', value: 'Ankle', label: 'Ankle' },
                { id: 'hand', value: 'Hand', label: 'Hand' },
                { id: 'elbow', value: 'Elbow', label: 'Elbow' },
                { id: 'back', value: 'Back', label: 'Back' },
                { id: 'foot', value: 'Foot', label: 'Foot' },
                { id: 'wrist', value: 'Wrist', label: 'Wrist' },
                { id: 'hip', value: 'Hip', label: 'Hip' },
                { id: 'neck', value: 'Neck', label: 'Neck' }
            ];


        document.addEventListener('DOMContentLoaded', function () 
        {
            const container = document.querySelector('#checkbox-container'); // Adjust the selector to match the location where you want to insert checkboxes
            
            var extIntrest = '<?php if(!empty($ExtAppData->interests)){ echo $ExtAppData->interests; }?>';
            
            if(extIntrest != ''){
                var interestArray = extIntrest.split(", ");
            }

            // Create the checkboxes dynamically
            const checkboxHtml = joints.map(joint => {
                if(interestArray != '' && typeof interestArray !== 'undefined'){
                    var isChecked = interestArray.includes(joint.value) ? 'checked' : '';
                }
                
                return `
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="mb-3">
                            <label class="mb-0 d-flex align-items-center gap-2">
                                <input class="joints" type="checkbox" id="${joint.id}" name="interests[]" value="${joint.value}" ${isChecked}>
                                ${joint.label}
                            </label>
                        </div>
                    </div>
                `;
            }).join('');

            // Insert the generated HTML into the container
            container.innerHTML += checkboxHtml;
            
            if (interestArray != '' && typeof interestArray !== 'undefined' && interestArray.length >= 2) {
                $('.joints:not(:checked)').prop('disabled', true);
            }
        });

        var PushedImg = [];
        
        const uploadedFilesContainer = document.querySelector('.uploaded-files');

        <?php
            if (!empty($ExtAppData->medicalDocuments)) {
                $ExtmedicalDocuments = $ExtAppData->medicalDocuments;
                $extClose = 'extClose';
            } elseif (!empty($LastAppointment)) {
                $ExtmedicalDocuments = $LastAppointment->medicalDocuments;
                $extClose = '';
            } else {
                $ExtmedicalDocuments = $user->medicalDocuments;
                $extClose = 'extClose';
            }
        ?>


        var ExtmedicalDocuments = <?php echo json_encode($ExtmedicalDocuments); ?>;

        if(ExtmedicalDocuments != null && ExtmedicalDocuments != '' && typeof ExtmedicalDocuments !== 'undefined'){
            var medicalDocumentsArray = ExtmedicalDocuments.split(",");
            
            var fileInput = medicalDocumentsArray;
            const serverpath = "{{ URL('/') }}/public/patient_reports/";
            
            const files = fileInput;
            uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

            Array.from(files).forEach(file => {
                const fileName = file; // You are assigning 'file' to 'fileName', but this variable isn't used, you can omit this line.

                // Create a new file item
                const fileItem = document.createElement('div');
                fileItem.className = 'd-flex gap-2 align-items-center';
                
                const closeButton = document.createElement('a');
                closeButton.href = '#';
                closeButton.className = 'close <?php echo $extClose; ?>';
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
                    const AppId = '<?php echo $LastAppointment->id; ?>'; 

                    // Perform AJAX request to delete the file
                    fetch('{{ route("delete.medicalDoc") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            AppId: AppId,
                            filename: filename
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                        // alert('File deleted successfully.');
                        fileItem.remove();
                        } else {
                            alert('Failed to delete file.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the file.');
                    });
                });

            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var fileInput = document.getElementById('medicalDocuments');
            const uploadedFilesContainer = document.querySelector('.uploaded-files');
            
            fileInput.addEventListener('change', function() {
            const files = fileInput.files;

            // uploadedFilesContainer.innerHTML = ''; // Clear previous file entries

            Array.from(files).forEach(file => {

                PushedImg.push(file);

                var dataTransfer = new DataTransfer();

                if(PushedImg != ''){
                    for (var i = 0; i < PushedImg.length; i++) {
                        dataTransfer.items.add(PushedImg[i]);
                    }

                    // Set the files to the input field
                    $('#medicalDocuments')[0].files = dataTransfer.files;
                }

                // const dataTransfer = new DataTransfer();
                // dataTransfer.items.add(file);
                // fileInput.files = dataTransfer.files;



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
                    // Find the index of the file in the PushedImg array
                    const index = PushedImg.indexOf(file);
                    if (index > -1) {
                        PushedImg.splice(index, 1); // Remove the file from the array
                    }

                    // Update the input field files
                    dataTransfer.clearData();
                    PushedImg.forEach(file => dataTransfer.items.add(file));
                    $('#medicalDocuments')[0].files = dataTransfer.files;

                    fileItem.remove();
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

    document.getElementById('userForm').addEventListener('submit', function(event) {
        var appType = '<?php echo $SessionappointmentType;?>';
        if(appType != '' && appType != 'undefined' && appType == 'Report Review'){
            if((PushedImg == '' || PushedImg == null) && (ExtmedicalDocuments == '' || ExtmedicalDocuments == null)){
                event.preventDefault();
                alert('Please upload minimum one medical document.');
            }
        }
    });

</script>
</main>
</body>

<script>
// $(document).ready(function() 
// {
//     // Push a state to the history to disable back navigation
//     history.pushState(null, null, window.location.href);

//     // Prevent back button by pushing state when popstate event is triggered
//     window.onpopstate = function(event) {
//         history.pushState(null, null, window.location.href);
//     };

//     // Additionally, prevent default action for popstate events
//     window.addEventListener('popstate', function(event) {
//         history.pushState(null, null, window.location.href);
//     }, false);


//     window.addEventListener('popstate', function(event) {
//         event.preventDefault(); // Prevent the default action
//         history.pushState(null, null, window.location.href);
//     });


//     // Set initial state and hash
//     history.pushState(null, null, window.location.href);
//     window.location.hash = "no-back";

//     // Prevent back navigation by manipulating the state and hash
//     window.onpopstate = function() {
//         if (window.location.hash === "#no-back") {
//             history.pushState(null, null, window.location.href);
//         }
//     };

//     // Additionally handle hash change event
//     $(window).on('hashchange', function() {
//         if (window.location.hash !== "#no-back") {
//             window.location.hash = "no-back";
//         }
//     });


//     history.pushState(null, null, window.location.href);
//     window.onpopstate = function(event) {
//         window.location.href = "{{ URL('/') }}/patient-dashboard"; // Change to your desired redirect URL
//     };


// });


</script>

</html>
@endsection