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
        
    </style>


<h6 class="mb-3"><a href="{{ url('/') }}/book_appointment">Back</a></h6>

    <form id="frm_save" action="{{ url('/') }}/book_appointment" method="POST">
    @csrf
         
    <div class="all-doctors bg-white p-3 mb-3 d-flex align-items-center justify-content-space-between gap-4 for-sm-device">
      
    <input type="hidden" id="search" name="search" value="{{ $doctor[0]->first_name }}">

      <input type="hidden" id="selected_doctor_id" name="doctor_id" value="{{ $doctor[0]->id }}">
      
      <div class="doctor-details d-flex gap-3 align-items-center w-100">
                    
        <div class="doc-smimg">
            
                    <?php if(!empty($doctor[0]->profile_photo))
                    {
                    ?>
                    <img src="{{ url('/') }}/public/doctor_photos/{{ $doctor[0]->profile_photo }}">
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
            
            <div class="doc-sm-content">
            {{ 'Dr. ' }} {{ $doctor[0]->first_name }} {{ $doctor[0]->last_name }}<span>
                {{ $doctor[0]->speciality }}</span><span>
                    {{ $doctor[0]->degree }}</span>
                    <span>
                    {{ $doctor[0]->experience }}</span>
            </div>
                    
                    
            </div>
                <div class="doctor-icon d-flex gap-3">
                    <a href="#" class="icon1"><img src="{{ url('/public/frontend/img/Layer 4(2).png') }}"></a>
                    <a href="#" class="icon2"><img src="{{ url('/public/frontend/img/Vector(40).png') }}"></a>
                    <a href="#" class="icon3"><img src="{{ url('/public/frontend/img/Group 9617(2).png') }}"></a>
                </div>
            </div>
        
            <div class="all-doctors bg-white p-3 mb-3 align-items-center">
                <!-- <h5 class="text-dark">Speciality</h5>
                <p>
                {{ $doctor[0]->speciality }}
                </p>

                <h5 class="text-dark">Experience</h5>
                <p>
                {{ $doctor[0]->experience }}
                </p>

                <h5 class="text-dark">Degree</h5>
                <p>
                {{ $doctor[0]->degree }}
                </p> -->

                <h5 class="text-dark">About {{ 'Dr. ' }} {{ $doctor[0]->first_name }} {{ $doctor[0]->last_name }} </h5>
                <p>
                {{ $doctor[0]->about }}
                </p>
                <button type="submit" class="btn btn-book-appointment btn-lg" name="btnSubmit" id="submit">Book An Appointment</button>
            </div>

        </form>                                    
        
      </div>
      

</main>
</div>
<script>
 $(document).ready(function() 
    {
         // add remova active class from li
            console.log($('#selected_doctor_id').val());
            
            fetchDates({{ $doctor[0]->id }});
            
            const messageElement = document.getElementById('no-timeslot-message');
            const label = document.getElementById('choose-timeslot-label');
            const slot_select = document.getElementById('slot_id');
                        
            slot_select.classList.add('hidden');
            messageElement.classList.add('hidden'); // Hide no dates message
            label.classList.add('hidden'); // Show the choose date label
            
            $('#radio-buttons-container-AvailableDates').on('click', 'li', function() 
             {
                
                // alert($(this).attr("id"));
                // alert($(this).attr("data-id"));
                
                var selected_date_id = $(this).attr("data-dateId");
                
                
                $('#AvailableDate_'+selected_date_id).attr("checked","checked")
                
                $('#selected_date_id').empty();
                
                $('#selected_date_id').val(selected_date_id);
                
                console.log($('#selected_date_id').val());
                
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
            
            console.log($('#selected_timeslot_id').val());
            
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
        
        // Use MutationObserver to detect dynamic changes and update button state
        const observer = new MutationObserver(() => {
            // updateSubmitButtonState();
        });
        
        // Observe changes to the body or a specific container where elements are appended
        observer.observe(document.body, { childList: true, subtree: true });
        // End :: disable save btn
        
        var form = document.getElementById('frm_save');
        // Add an event listener for form submission
        form.addEventListener('submit', function(event) 
        {
                event.preventDefault();
                // Retrieve form values
                var doctorId = $('input[name="doctor_id"]').val();
                var selectedDate = $('input[name="customRadio_AvailableDate"]:checked').val();
                var slotId = $('input[name="customRadio_timeslot"]:checked').val();
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
         
    });  
    
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

                generateRadioButtons_AvailableDates(data.dates); // Adjust based on the actual response format
                
                document.getElementById('choose-date-label').classList.remove('hidden');
                
                // console.log('API Response:', data); // Log the response for debugging

                const container = document.getElementById('radio-buttons-container-AvailableDates');
                const messageElement = document.getElementById('no-dates-message');
                const label = document.getElementById('choose-date-label');
                
                if (data.dates && Array.isArray(data.dates)) {
                    if (data.dates.length === 0) {
                        // No dates found
                        container.innerHTML = ''; // Clear any existing content
                        messageElement.classList.remove('hidden'); // Show no dates message
                        label.classList.add('hidden'); // Hide the choose date label
                    } else {
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
                console.error('Error fetching dates:', error);
            }
        }
        
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
            const container = $('#radio-buttons-container-AvailableDates');
            container.empty(); // Clear existing content
            
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
            
            var doctor_id = {{ $doctor[0]->id }};
            
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
            
            if (doctor_id != "" && selectedRadioId != "") {
                $.ajax({
                    url: '{{ url("/") }}/get-timeslots/'+doctor_id+'/'+selectedRadioId,
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
        
    </script>
        
<!-- Jquery -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
</body>
</html>
@endsection