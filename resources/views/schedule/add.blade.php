@extends('layouts.app')

@section('head')
<!-- <style>
         form {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: green;
            color: white;
        }
    </style>  -->
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Schedule'])
    
    <?php
    $sql = "SELECT * FROM dbl_users where user_type = 'doctor'";
    $users = DB::select($sql);
    $doctors = collect($users);					                
    
    $sql = "SELECT * FROM dbl_users where user_type = 'patient'";
    $users = DB::select($sql);
    $patients = collect($users);					                                                          


    ?>
        <div class="row mt-4 mx-4">
            <div class="col-12">
                
                    <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Add Appointment</h6>
                            </div>
                            <div class="card-body px-5 pt-0 pb-2">
                                
                                <form id="frm_save" action="{{ URL('/create-schedule') }}" method="POST"  enctype="multipart/form-data">
                                    
                                    @csrf
                                    <label for="docto">{{ __('Doctor') }}</label>
                                    <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" autocomplete="doctor" required>
                                        <option value="" selected >@lang('Choose')...</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ old('doctor', $doctor->id) }}">{{ old('doctor', $doctor->first_name) }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <label for="patient">{{ __('Patients') }}</label>
                                    <select id="patient_id" name="patient_id" class="form-control @error('patient_id') is-invalid @enderror" autocomplete="patient" required>
                                        <option value="" selected >@lang('Choose')...</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ old('patient', $patient->id) }}">{{ old('patient', $patient->first_name) }}</option>
                                        @endforeach
                                    </select>
                                    
                                    
    
                                    <!--<label for='title'>{{ __('title') }}</label>-->
                                    <!--<input type='text' class='form-control' id='title' name='title'>-->

                                    <label for="start">{{__('Select Appointment Date')}}</label>
                                    <input type='date' class='form-control' id='selected_date' name='selected_date' required value='{{ now()->toDateString() }}' required>
                                    
                                    <br>
                                    <input type="button" id="check" value="Check Available Time Slots" class="btn btn-success" />
                                    
                                    <!--<label for="end">{{__('End')}}</label>-->
                                    <!--<input type='datetime-local' class='form-control' id='end' name='end' required value='{{ now()->toDateString() }}'>-->
                                    
                                    <br>
                                    <label for="patient">{{ __('Select Time slot') }}</label>
                                    <select id="slot_id" name="slot_id" class="form-control">
                                    </select>

                                    <br>
                                     <div class="form-group">
                                        <label for="files">Select Files:</label>
                                        <input type="file" name="files[]" id="files" class="form-control" multiple>
                                    </div>
                                    
                                    <label for="description">{{__('Notes')}}</label>
                                    <input type="text"  class='form-control' id="description" name="description" />

                                    <!-- <label for="color">{{__('Color')}}</label>
                                    <input type="color" id="color" name="color" /> -->
                                    <br>
                                    
                                    <button type="submit" name="btnSubmit" id="submit" class="btn btn-success">Save</button>
                                    
                                </form>
                                    
                            </div>
                    </div>
            </div>
        </div>
<script>
    $(document).ready(function() {
        
        var form = document.getElementById('frm_save');

    // Add an event listener for form submission
    form.addEventListener('submit', function(event) {
        // Prevent the default form submission
                event.preventDefault();
        
                // Retrieve form values
                var doctorId = document.getElementById('doctor_id').value;
                var selectedDate = document.getElementById('selected_date').value;
                var slotId = document.getElementById('slot_id').value;
        
                // Initialize an array to hold error messages
                var errors = [];
        
                // Validation checks
                if (!doctorId) {
                    errors.push('Please select a doctor.');
                }
        
                if (!selectedDate) {
                    errors.push('Please select an appointment date.');
                }
        
                if (!slotId) {
                    errors.push('Please select a time slot.');
                }
        
                // If there are errors, display them and prevent form submission
                if (errors.length > 0) {
                    alert(errors.join('\n'));
                    return false;
                }
        
                // If all validations pass, submit the form programmatically
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';        // Set the type to 'hidden'
                hiddenInput.name = 'form_submitted'; // Set the name of the input field
                hiddenInput.value = 'true';         // Set a value for the hidden input
                
                // Append the hidden input field to the form
                form.appendChild(hiddenInput);
                
                // Submit the form
                submitForm(form)
                
            });
        
        function submitForm(form) {
            const submitFormFunction = Object.getPrototypeOf(form).submit;
            submitFormFunction.call(form);
        }
        
        $('#check').click(function() {
            var doctor_id =  $("#doctor_id").val();
            var selected_date = $("#selected_date").val();
            
            if(selected_date === "")
            {
                alert("Please select appointment date");
                return false;
            }
            
            if(doctor_id === "")
            {
                alert("Please select doctor");
                return false;
            }
            
            if (doctor_id != "" && selected_date != "") {
                $.ajax({
                    url: '{{ url("/") }}/get-timeslots/'+ doctor_id+'/'+selected_date,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#slot_id').empty();
                        $('#slot_id').append($('<option>').text('Select Timeslot').attr('value', ''));
                        $.each(data.schedules, function(index, schedule) {
                            $('#slot_id').append($('<option>').text(schedule.start + ' - ' + schedule.end).attr('value', schedule.id));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching timeslots');
                    }
                });
            } else {
                $('#slot_id').empty();
                $('#slot_id').append($('<option>').text('Select Timeslot').attr('value', ''));
            }
        });
    });
</script>

@endsection

