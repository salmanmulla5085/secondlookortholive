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

.hidden {
    display: none;
}

.visible {
    display: block;
}


  .transition {
            transition: all .3s ease-out;
        }

        input[type="radio"] {
            visibility: hidden;
            height: 0;
            width: 0;
        }

        /* Change background color when radio button is selected */
        input[type="radio"]:checked + .radio_label {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }

        /* Custom label styling */
        .radio_label {
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #212529;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
            text-align: center;
            text-transform: capitalize;
        }

.subtotal1 {
    background: #E6E6E6;
    border-radius: 4px;
    padding: 0 15px;
    font-weight: 600;
    color: #000;
    max-width: fit-content;
    gap: 1rem;
    height: 38px;
    display: flex;
    align-items: center;
}        
    </style>
    
    
<div class="all-doctors bg-white p-3 mb-3">

 @if(!empty($doctors) && count($doctors) > 0)  
 
    <h5 class="mb-3 text-dark">All Available Doctors</h5>  
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
                            
     <form id="frm_save" action="{{ url('/') }}/book_appointment" method="POST"  enctype="multipart/form-data">
         @csrf
    
         <input type="hidden" id="selected_doctor_id" name="doctor_id">

         <div class="all-doctors1">
         <ul>
          
           @foreach($doctors as $doctor)
           
            <li class="doctorLi_{{ $doctor->id }}" data-doctorId="{{ $doctor->id }}">
                <input class="doctor_id" id="DoctorId_{{ $doctor->id }}" type="radio" name="customRadio_doctor" value="{{ $doctor->id }}">
                <div class="doc-card">
                 <div class="doc-img">
                    <?php if(!empty($doctor->profile_photo))
                    {
                    ?>
                    <img src="{{ url('/') }}/public/doctor_photos/{{ $doctor->profile_photo }}">
                    <?php
                    }
                    else
                    {
                    ?>
                    <img src="{{ url('/public/doctor_photos/doctor.jpg') }}">
                    <?php
                    }
                    ?> 
                 </div>
                 <div class="doc-contents"><span>{{ 'Dr. ' . $doctor->first_name . ' ' . $doctor->last_name}}</span><a href="{{ url('/doctor-details') }}/{{ $doctor->id}}">View Details</a></div>
                </div>
                
                    
            </li>
           @endforeach
           
         </ul>
        </div>

        <h5 class="text-dark mb-3 hidden choose-plan-label plan_heading">Select Consultation Plan</h5>
        <div class="row plan_drop">
            <div class="col-12 col-md-3 col-lg-4">
                <label class="hidden w-100 choose-plan-label">
                    <span style="color: red;"></span>
                    <div>
                    <select class="form-select" id="appointmentType" name="appointmentType" @if(!empty($ExtAppData->appointmentType)) disabled @endif required>
                                        <option value="" disabled selected>Select Appointment Type</option>                                                        
                                        @foreach($tbl_plans as $k => $v)
                                            @if(!empty($ExtAppData->appointmentType))
                                                <option 
                                                data-amount="{{ $v->plan_amount }}"  
                                                value="{{ $v->plan_type }}"
                                                {{ old('appointmentType', $ExtAppData->appointmentType ?? '') == $v->plan_type ? 'selected' : '' }}
                                                >
                                                @if($v->plan_type == 'Report Review')
                                                {{ 'Report Review Request' }}
                                                @else
                                                {{ $v->plan_type }}
                                                @endif

                                                </option>
                                            @else
                                                <option 
                                                data-amount="{{ $v->plan_amount }}"  
                                                value="{{ $v->plan_type }}"
                                                {{ old('appointmentType', $selected_plan->plan_type ?? '') == $v->plan_type ? 'selected' : '' }}
                                                >
                                                @if($v->plan_type == 'Report Review')
                                                {{ 'Report Review Request' }}
                                                @else
                                                {{ $v->plan_type }}
                                                @endif
                                                </option>
                                            @endif
                                        @endforeach

                                        </select>
                    </div>
                    @if(!empty($ExtAppData->appointmentType))
                                    <input type="hidden" id="appointmentType_new" name="appointmentType" value="{{ $ExtAppData->appointmentType }}">
                    @endif
                </label>
            </div>
            
            <div class="col-12 col-md-9 col-lg-8 amount_div" style="display:none;">
                <div class="mb-3">
                    <div class="subtotal1 d-flex justify-content-between ">
                        AMOUNT: <span id="total_amount">@if(isset($selected_plan->plan_amount))    
                        {{ $selected_plan->plan_amount }}
                        @endif</span>
                    </div>    
                </div>
            </div>
        </div>
    
        <label id="choose-date-label" class="hidden">
            <h5 class="text-dark">Select from the available dates</h5><span style="color: red;"></span>
            <div>
                Please select the below date
            </div>
        </label>
             <!--no need to send this input to server since slot_id has date-->
            <input type="hidden" id="selected_date_id" name="selected_date_id">
            <div class="all-dates">
                <ul class="caregories1 dates1 mb-4" id="radio-buttons-container-AvailableDates">
                </ul>
            </div>    
            <p id="no-dates-message" class="hidden">No dates found</p>
        <br>
        
        <label for="slot_id" id="choose-timeslot-label" class="hidden"><h5 class="text-dark">Select from the available time slot</h5>
            <div>
                Please select the below time slot
            </div><span style="color: red;"></span></label>
        <input type="hidden" id="selected_timeslot_id" name="selected_timeslot_id">
        <input type="hidden" id="app_id" name="app_id" value="<?php if($appointment_id && !empty($appointment_id)){ echo $appointment_id; } ?>">
        <ul class="caregories1 mb-4" id="slot_id" class="hidden;">
        </ul>
        
        <p id="no-timeslot-message" class="hidden">No time slots found</p>
     

        <!--<button type="button" class="btn btn-book-appointment btn-lg">Book an appointment</button>-->
    
        <button type="submit" class="btn btn-book-appointment submitDoctor btn-lg" name="btnSubmit" id="submit">Book An Appointment</button>

        </form>                                    
 @else
    <h5 class="mb-3 text-dark">All Available Doctors</h5>
    <div class="alert alert-danger">
        No doctors found.
    </div>
 @endif 
 
</div>

<script>

    $(document).ready(function() 
    {


        var extDoctorId = '<?php if(!empty($ExtAppData->doctor_id)){ echo $ExtAppData->doctor_id; }?>';
        
        if(extDoctorId != '' && typeof extDoctorId !== 'undefined'){
            $('li[data-doctorid="' + extDoctorId + '"]').addClass('active');
            $('#DoctorId_' + extDoctorId).attr('checked', 'checked');
            $('#selected_doctor_id').val(extDoctorId);
            fetchDates(extDoctorId);
        }
        
         // add remova active class from li  
         
        var SearchStr = '<?php if(!empty($SearchString)){ echo $SearchString; }?>';

        if(SearchStr != '' && typeof SearchStr !== 'undefined'){
            var doctor_id = '<?php if(!empty($doctor_id)){ echo $doctor_id; }?>';
            $(".amount_div").css("display","none");
            $('.all-doctors1 li.active').removeClass('active');

            if(doctor_id != '' && typeof doctor_id !== 'undefined'){
                $('.all-doctors1 .doctorLi_'+doctor_id).addClass('active');
            } else {
                $('.all-doctors1 li:first').addClass('active');
            }

            var doctor_id = $('.all-doctors1 li:first').attr("data-doctorId");
            get_time_slot(doctor_id);
        }

        $('.all-doctors1').on('click', 'li', function() 
         {

            $(".amount_div").css("display","none");

            $('.all-doctors1 li.active').removeClass('active');
            $(this).addClass('active');

            // $('.choose-plan-label').addClass('hidden');
            $('#appointmentType').val('Report Review').change();

            // alert($(this).attr("id"));
            
            var doctor_id = $(this).attr("data-doctorId");
            get_time_slot(doctor_id);
            
         }); 

        function get_time_slot(doctor_id){
            $('#DoctorId_'+doctor_id).attr("checked","checked")
            
            $('#selected_doctor_id').empty();
            
            $('#selected_doctor_id').val(doctor_id);           
            
            console.log($('#selected_doctor_id').val());
            
            var elements = document.getElementsByClassName('choose-plan-label');

            for(var i = 0; i < elements.length; i++)
            {
                elements[i].classList.remove('hidden');
            }
            
            
            const messageElement = document.getElementById('no-timeslot-message');
            const label = document.getElementById('choose-timeslot-label');
            const slot_select = document.getElementById('slot_id');
                        
            slot_select.classList.add('hidden');
            messageElement.classList.add('hidden'); // Hide no dates message
            label.classList.add('hidden'); // Show the choose date label
        }

        // var selected_option = $("#appointmentType option:selected");
        
        // if(selected_option != ''){
            
        //     ChangeAmount(selected_option)

        //     if(selected_option != "Report Review")
        //     fetchDates($('#selected_doctor_id').val());s
        // }        
        
        $("#appointmentType").change(function()
        {
            $(".amount_div").css("display","block");

            // fetchDates($('#selected_doctor_id').val());

            var selected_option = $("#appointmentType option:selected");           

            if(selected_option.val() != "Report Review")
            {
            document.getElementById('choose-date-label').classList.remove('hidden');

            fetchDates($('#selected_doctor_id').val());           
            }

            if(selected_option.val() == "Report Review")
            {
                $('#submit').empty();
                $('#submit').html("Book Report Review");
                $("#PageName").empty();
                $('#PageName').html("Book Report Review");

                document.getElementById('choose-date-label').classList.add('hidden');
                
                $("#radio-buttons-container-AvailableDates").empty();

                document.getElementById('choose-timeslot-label').classList.add('hidden');                

                $("#slot_id").empty();

            }
            else
            {
                $('#submit').empty();
                $('#submit').html("Book An Appointment");
                $("#PageName").empty();
                $('#PageName').html("Book An Appointment");

            }

            ChangeAmount(selected_option);
        });  
        
        function ChangeAmount(selected_option){
            var selected_amount = selected_option.data("amount");
            // console.log(selected_amount);
            $("#total_amount").html(selected_amount);
            // console.log($("#total_amount").html());
        }

        $('#radio-buttons-container-AvailableDates').on('click', 'li', function() 
         {
            
            // alert($(this).attr("id"));
            // alert($(this).attr("data-id"));
            document.getElementById('choose-timeslot-label').classList.remove('hidden');                            
            
            var selected_date_id = $(this).attr("data-dateId");
            
            
            $('#AvailableDate_'+selected_date_id).attr("checked","checked")
            
            $('#selected_date_id').empty();
            
            $('#selected_date_id').val(selected_date_id);
            
            // console.log($('#selected_date_id').val());
            
            fetchTimeslots(selected_date_id); // Fetch time slots for the selected date
            
        }); 
        
        $('#slot_id').on('click', 'li', function() 
         {
            
            // alert($(this).attr("id"));
            // alert($(this).attr("data-id"));
            
            var selected_timeslot_id = $(this).attr("data-timeslotId");
            
            $('#TimeSlot_'+selected_timeslot_id).attr("checked","checked")
            
            $('#selected_timeslot_id').empty();
            
            $('#selected_timeslot_id').val(selected_timeslot_id);
            
            // console.log($('#selected_timeslot_id').val());
            
            
        }); 

        $('#doctor_id').change(function() {
            // var doctor_id = $(this).val();
            // fetchDates(doctor_id);
            
            // const messageElement = document.getElementById('no-timeslot-message');
            // const label = document.getElementById('choose-timeslot-label');
            // const slot_select = document.getElementById('slot_id');
                        
            // slot_select.classList.add('hidden');
            // messageElement.classList.add('hidden'); // Hide no dates message
            // label.classList.add('hidden'); // Show the choose date label
            
        });
        
        
        // var selectedRadioId = $('input[name="customRadio"]:checked').attr('id');
        
        // const container = $('#radio-buttons-container-AvailableDates');
        // container.on('change', 'input[name="customRadio_doctor"]', function() {
        //             const selectedRadioId = $(this).attr('id'); // Get the ID of the selected radio button
        //             console.log(selectedRadioId);
        //             fetchTimeslots(selectedRadioId); // Fetch time slots for the selected date
        //         });
                
        $('#doctor_id_old').change(function() {
            // var doctor_id = $(this).val();
            // if (doctor_id) {
            //     $.ajax({
            //         url: '{{ url("/") }}/get-available-dates/'+ doctor_id,
            //         type: 'GET',
            //         dataType: 'json',
            //         success: function(data) {
            //             $('#selected_date').empty();
            //             $('#slot_id').append($('<option>').text('Select Timeslot').attr('value', ''));
            //             $.each(data.schedules, function(index, schedule) {
            //                 $('#slot_id').append($('<option>').text(schedule.start + ' - ' + schedule.end).attr('value', schedule.id));
            //             });
            //         },
            //         error: function(xhr, status, error) {
            //             console.error('Error fetching timeslots');
            //         }
            //     });
            // } else {
            //     $('#slot_id').empty();
            //     $('#slot_id').append($('<option>').text('Select Timeslot').attr('value', ''));
            // }
        });
    
        
        //start :: diable the save button 
        function updateSubmitButtonState() {
            // Get the submit button
            const $submitButton = $('#frm_save');
            
            // Check if the elements are present
            const $slotIdElement = $('#slot_id');
            const $customRadioElements = $('input[name="customRadio"]');
            
            // Disable the submit button if any of the elements are not present
            if ($slotIdElement.length === 0 || $customRadioElements.length === 0) {
                $submitButton.prop('disabled', true);
            } else {
                $submitButton.prop('disabled', false);
            }
        }
        
        
        // updateSubmitButtonState();
        
        // Use MutationObserver to detect dynamic changes and update button state
        const observer = new MutationObserver(() => {
            // updateSubmitButtonState();
        });
        
        // Observe changes to the body or a specific container where elements are appended
        observer.observe(document.body, { childList: true, subtree: true });
        // End :: disable save btn
        
        $(".submitDoctor").click(function(){
            if (!$('.all-doctors1 li').hasClass('active')) {
                alert('Please select a doctor.');
            }
        });
        
        var form = document.getElementById('frm_save');
        // Add an event listener for form submission
        form.addEventListener('submit', function(event) {
        // Prevent the default form submission
                event.preventDefault();

                if (!$('.all-doctors1 li').hasClass('active')) {
                    errors.push('Please select a doctor.');
                }
        
                // Retrieve form values
                var doctorId = $('input[name="doctor_id"]').val();
                var selectedDate = $('input[name="customRadio_AvailableDate"]:checked').val();
                var slotId = $('input[name="customRadio_timeslot"]:checked').val();
                // var slotId = document.getElementById('slot_id').value;
        
                // Initialize an array to hold error messages
                var errors = [];
        
                // Validation checks
                if (!doctorId) {
                    errors.push('Please select a doctor.');
                }
        
                
                var selected_option = $("#appointmentType option:selected");           

                if(selected_option.val() != "Report Review")
                {                
                    if (!selectedDate) {
                        errors.push('Please select an appointment date.');
                    }
            
                    if (!slotId) {
                        errors.push('Please select a time slot.');
                    }
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
        
        
        
        

    });

          // Function to format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            // const options = { weekday: 'short', day: '2-digit', month: 'long' };
            
            let options = { weekday: 'short'};
    
            // Get the weekday
            let weekday = date.toLocaleDateString('en-US', options);
    
            // Get the day of the month
            let day = date.getDate();
    
            // Format the date as "27 Fri"
            // return `${weekday} ${day}`;
            
            return `${weekday}<span>${day}</span>`;
            
            
            
        }
        

        // Function to generate radio buttons
        function generateRadioButtons_AvailableDates(dates) {
            if (!Array.isArray(dates)) {
                console.error('Expected an array of dates');
                return;
            }
            const container = $('#radio-buttons-container-AvailableDates');
            container.empty(); // Clear existing content
         
            console.log("insde func generateRadioButtons_AvailableDates()");
            console.log("Array.isArray(dates) : "+Array.isArray(dates)); // Should print true if dates is an array
            console.log("dates: "+dates); // Inspect the value of dates
            
             dates.forEach((item) => {
                    const { id, date } = item; // Destructure item
                    const radioId = id; // Use id directly for the radio button ID
        
                    const formattedDate = formatDate(date); // Format the date for display
        
                    const radioButtonHTML = `
                        <li data-dateId="${radioId}">
                        <input type="radio" id="AvailableDate_${radioId}" name="customRadio_AvailableDate" value="${date}">
                        <label for="AvailableDate_${radioId}" class="btn">${formattedDate}</label>
                      </li>
                    `;
                    
                    container.append(radioButtonHTML); // Append the HTML to the container
                });
                // Event delegation for dynamically created radio buttons
        }
        
        function fetchTimeslots(selectedRadioId)
        {
            
            var doctor_id = $('#selected_doctor_id').val();
            
            console.log('Selected doctorId ID:', doctor_id);
            console.log('Selected Radio ID:', selectedRadioId);
            
            if(selectedRadioId === "")
            {
                alert("Please select appointment date");
                return false;
            }
            
            if(doctor_id === "")
            {
                alert("Please select doctor");
                return false;
            }
            
            @if (session()->has('user'))
                @php
                    $user = session('user',[]);
                @endphp

                var patient_id = {{ $user['id'] }}

            @endif

            

        if (doctor_id != "" && selectedRadioId != "" && patient_id != "") 
        {
                $.ajax({
                    url: '{{ url("/") }}/get-timeslots/'+doctor_id+'/'+selectedRadioId+'/'+patient_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        
                        const messageElement = document.getElementById('no-timeslot-message');
                        const label = document.getElementById('choose-timeslot-label');
                        const slot_select = document.getElementById('slot_id');
                        
                        if (data.schedules && Array.isArray(data.schedules)) {
                            if (data.schedules.length === 0) {
                                // No dates found
                                $('#slot_id').empty();
                                messageElement.classList.remove('hidden'); // Show no dates message
                                label.classList.add('hidden'); // Hide the choose date label
                            } else {
                                // Data found
                                $('#slot_id').empty();
                                
                                // $('#slot_id').append($('<option>').text('Select Timeslot').attr('value', ''));
                                $.each(data.schedules, function(index, schedule) 
                                {
                                    // $('#slot_id').append($('<option>').text(schedule.start + ' - ' + schedule.end).attr('value', schedule.id));
                                    const radioButtonHTML = `
                                        <li data-timeslotId="${schedule.id}">
                                            <input type="radio" id="TimeSlot_${schedule.id}"  name="customRadio_timeslot" value="${schedule.start}">
                                            <label for="TimeSlot_${schedule.id}" class="btn">${schedule.start}</label>
                                        </li>
                                    `;
                                    
                                    $('#slot_id').append(radioButtonHTML);
                                    
                                });
                                
                                slot_select.classList.remove('hidden');
                                messageElement.classList.add('hidden'); // Hide no dates message
                                label.classList.remove('hidden'); // Show the choose date label
                            }
                        } else {
                            console.error('Unexpected API response format');
                            $('#slot_id').empty(); // Clear any existing content
                            messageElement.classList.remove('hidden'); // Show no dates message
                            label.classList.add('hidden'); // Hide the choose date label
                        }
                        
                        
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching timeslots');
                    }
                });
            } else {
                $('#slot_id').empty();
                $('#slot_id').append($('<option>').text('Select Timeslot').attr('value', ''));
            }
        }    

        // Fetch dates from server and generate radio buttons
        async function fetchDates(doctorId) {
            try {
                
                if (!doctorId) {
                        console.error('No doctor selected');
                        return;
                }
                    
                    
                const response = await fetch('{{ url("/") }}/get-available-dates/'+ doctorId); // Replace with your API endpoint
            
                 if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                const data = await response.json();
                
                console.log('RAW data.dates'+data.dates);

                document.getElementById('choose-date-label').classList.remove('hidden');
                const container = document.getElementById('radio-buttons-container-AvailableDates');
                const messageElement = document.getElementById('no-dates-message');
                const label = document.getElementById('choose-date-label');
                

                if (!Array.isArray(data.dates)) {
                        console.error('Expected an array of dates');
                        container.innerHTML = ''; // Clear any existing content
                        messageElement.classList.remove('hidden'); // Show no dates message
                        label.classList.add('hidden'); // Hide the choose date label    
                        return;
                }                
                
                if (Array.isArray(data.dates)) {
                    if (data.dates.length === 0) {
                        // No dates found
                        container.innerHTML = ''; // Clear any existing content
                        messageElement.classList.remove('hidden'); // Show no dates message
                        label.classList.add('hidden'); // Hide the choose date label
                        return;
                    } 
                    
                    if (data.dates.length > 0) {
                        // Dates found
                        generateRadioButtons_AvailableDates(data.dates);
                        messageElement.classList.add('hidden'); // Hide no dates message
                        label.classList.remove('hidden'); // Show the choose date label
                    }
                } else {
                    console.error('Unexpected API response format');
                    container.innerHTML = ''; // Clear any existing content
                    messageElement.classList.remove('hidden'); // Show no dates message
                    label.classList.add('hidden'); // Hide the choose date label
                }
                
                
            } catch (error) {
                // console.error('Error fetching dates:', error);
            }
        }

        // Call fetchDates to load data
        
    
    
</script>


</div>



</main>
</div>

</body>
</html>
@endsection