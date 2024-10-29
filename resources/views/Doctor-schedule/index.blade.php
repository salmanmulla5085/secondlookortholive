@extends('frontend.layouts.dashboardMain')

@section('dashboardMain.container')


<style type="text/css">
    .fc .fc-popover {
        z-index: 1040 !important;
    }

    .fc .fc-more-popover .fc-popover-body {
        min-width: 220px;
        padding: 10px;
        overflow: hidden !important;
        overflow-y: scroll !important;
        min-height: 180px;
        max-height: 180px !important;
    }

    .fc-v-event {
        background-color: transparent;
        border: none;
        display: block;
    }

    .text-orange1 {
        color: #F37A12
    }

    /* phone call */
    .text-purpel1 {
        color: #9588E8;
    }

    /* report review */
    .text-tilt1 {
        color: #02C4B7
    }

    /* video call */

    .bg-orange1 {
        background: #F37A12
    }

    /* phone call */
    .bg-purpel1 {
        background: #9588E8;
    }

    /* report review */
    .bg-tilt1 {
        background: #02C4B7
    }

    /* video call */
    #patient-details1Modal .modal-content {
        border-radius: 0;
        border: none;
    }

    .phone-consults {
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 45px;
        padding: 0 1rem;
        font-size: 16px;
        font-weight: 700;
    }

    .pat-description ul {
        list-style: none;
        padding: 0;
    }

    .pat-description ul li span {
        color: #8d8d8d;
        font-weight: normal;
    }

    .pat-description ul li {
        color: #000012;
    }

    .detail_head {
        font-weight: 600;
    }

    @media screen and (max-width:992px) {
        #patient-details1Modal .modal-lg {
            max-width: 98%
        }
    }

    @media screen and (max-width:680px) {
        .bg-orange1 {
            flex-direction: column;
        }
    }


    .fc-daygrid-day-frame {
        height: 120px;
        /* Adjust this value to your desired height */
        overflow: hidden;
        /* Hide overflowing content */
    }


    .fc-event {
        /* border-color: rgb(78, 154, 6); 
            background-color: rgb(78, 154, 6); 
                */
        color: black;
    }

    .fc-daygrid-dot-event:hover {
        background-color: #fff !important;
        color: #000 !important;
    }

    /* Modal Styles */
    .modal-body .row {
        margin-bottom: 15px;
    }

    .modal-body .row .col-6 {
        font-weight: bold;
    }

    .modal-body .row .col-6 {
        font-weight: normal;
    }

    .report_event {
        background-color: #9588e8 !important;
    }

    .phone_event {
        background-color: #f37a12 !important;
    }

    .vdo_event {
        background-color: #02c4b7 !important;
    }
</style>
<main id="main-page">

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

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6">
                    <!-- Other content can go here -->
                </div>
            </div>

            <div class="card mb-4">
                <!-- <div class="card-header pb-0">
                    <h6>Booked Appointments:: <a href="{{ URL('Doctor-add-schedule') }}" class="btn btn-success" style="margin-left:20px">{{__('Add New')}}</a></h6>
                </div> -->

                <div class="card-body px-4 pt-4 pb-2">
                    <div id="calendar" style="width:100%; height:100vh"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <!-- Modal Structure -->
    <!-- Modal Structure -->

    <!-- Modal -->


    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="d-flex justify-content-between align-items-center gap-3 bg-color p-3 flex-column flex-md-row">
                        <h5 class="mb-0 text-white">Appointment- Details</h5>
                        <div class="d-flex gap-3 justify-content-between align-items-center">
                            <div class="d-flex gap-3 justify-content-between align-items-center">
                                <div class="phone-consults" id="head_eventAppointmentType"></div>
                                <div class="d-flex flex-column text-white">
                                    <span id="head_eventDate"></span>
                                    <span id="head_eventTime"></span>

                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="pt-4 pb-4 ps-4 pe-4 pat-description">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <ul class="d-flex gap-2 flex-column mb-0">
                                    <li>
                                        <span style="font-weight: 600">Date: </span> <span id="eventDate"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Time: </span> <span id="eventStartEnd"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Contact Number:</span> <span id="eventContactNumber"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Category:</span> <span id="eventCategory"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Symptoms:</span> <span id="eventSymptoms"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Appointment Type:</span> <span id="eventAppointmentType"></span>
                                    </li>
                            </div>
                            <div class="col-12 col-md-6">
                                <ul class="d-flex gap-2 flex-column mb-0">
                                    <li><span style="font-weight: 600">Joints:</span> <span id="eventInterests"></span></li>
                                    <!-- <li>
                                        <span style="font-weight: 600">Notes:</span> <span id="eventNotes"></span>
                                    </li> -->
                                    <li>
                                        <span style="font-weight: 600">Amount:</span> <span id="eventAmount"></span>
                                    </li>
                                    <li id="eventMeetingLink">
                                        <!-- <span style="font-weight: 600">Meeting: </span>
                                        <span id="eventMeetingLink"></span> -->
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Status:</span> <span id="eventStatus"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Medical Documents:</span> <span id="patientMedicalDocuments"></span>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 bg-color p-3">
                        <h5 class="mb-0 text-white">Patient Details</h5>
                    </div>
                    <div class="pt-4 pb-4 ps-4 pe-4 pat-description">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <ul class="d-flex gap-2 flex-column mb-0">
                                    <li>
                                        <span style="font-weight: 600">Name:</span> <span id="patientName">
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Gender:</span> <span id="patientGender"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Age:</span> <span id="patientAge"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Email:</span> <span id="patientEmail"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">Contact Number:</span> <span id="patientContactNumber"></span>
                                    </li>
                                </ul>

                            </div>
                            <div class="col-12 col-md-6">
                                <ul class="d-flex gap-2 flex-column mb-0">
                                    <li>
                                        <span style="font-weight: 600">Alternate Contact Number:</span> <span id="patientAlternateContactNumber"></span>
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">State:</span> <span id="patientState">
                                    </li>
                                    <li>
                                        <span style="font-weight: 600">City:</span> <span id="patientCity">
                                    </li>
                                    <li>
                                </ul>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



</main>
</div>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $('#accept_appointment').on('click', function() {
            const appointmentId = $('#eventModal').data('appointment-id'); // Assuming you set the appointment ID in the modal's data
            const url = "{{ route('appointments.confirm') }}";

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}", // CSRF token for security
                    appointment_id: appointmentId
                },
                success: function(response) {
                    if (response.success) {
                        // Change button label to 'Confirmed'
                        $('#accept_appointment').text('Confirmed');

                        // Change button class to 'btn-success'
                        $('#accept_appointment').removeClass('btn-primary').addClass('btn-success');
                    } else {
                        alert('Failed to confirm appointment: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error in confirming the appointment.');
                }
            });
        });
    });


    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        timeZone: 'UTC',
        events: '{{ route("Doctor.getEvents") }}',
        editable: true,
        dayMaxEvents: 2, // Show only 1 event, others will be collapsed into "+ more"
        eventContent: function(info) {
            console.log(info.event.extendedProps);

            if (info.event.extendedProps.state.STATE_NAME != null)
                console.log(info.event.extendedProps.state.STATE_NAME);

            if (info.event.extendedProps.city != null)
                console.log(info.event.extendedProps.city);

            console.log(info.event.extendedProps.appointment_start);
            console.log(info.event.extendedProps.appointment_end);
            console.log(info.event.extendedProps.medicalDocuments);

            var report = info.event.extendedProps.appointmentType.split(" ")[0];
            var consultation_type = info.event.extendedProps.appointmentType;

            // var start = new Date(info.event.extendedProps.appointment_start);
            // var formattedStartTime = formatTime(start);

            // var end = new Date(info.event.extendedProps.appointment_end);
            // var formattedEndTime = formatTime(end);

            // var FullTimeString = formattedStartTime+' - '+formattedEndTime;

            // var eventTitle = info.event.extendedProps.name+' '+FullTimeString;

            StartDateTimeString = info.event.startStr;

            var startTimePart = StartDateTimeString.substring(11, 16);

            console.log(startTimePart);

            EndDateTimeString = info.event.endStr;

            var endTimePart = EndDateTimeString.substring(11, 16);

            console.log(endTimePart);

            const FullTimeString = startTimePart + ' - ' + endTimePart;

            console.log(FullTimeString);

            var eventTitle = info.event.extendedProps.name + ': ' + FullTimeString;

            var eventElement = document.createElement('div');

            // Report Review Video Consultation  Phone Consultation
            var type_bg_color = "";
            if (consultation_type == 'Report Review')
                type_bg_color = "#9588E8";

            if (consultation_type == 'Phone Consultation')
                type_bg_color = "#F37A12";

            if (consultation_type == 'Video Consultation')
                type_bg_color = "#02C4B7";

            eventElement.style.backgroundColor = type_bg_color;

            eventElement.style.padding = "1px"; // Add 15px padding
            // eventElement.style.margin = "5px";   // Add 10px margin
            eventElement.style.fontSize = "12px"; // Add 10px margin

            eventElement.innerHTML = '<span class="js_loaded" style="cursor: pointer;"></span> ' + eventTitle;

            return {
                domNodes: [eventElement]
            };
        },

        eventDidMount: function(info) {
            // Add a click event to the "+ more" link
            if (info.el.querySelector('.fc-more')) {
                info.el.querySelector('.fc-more').addEventListener('click', function(e) {
                    e.preventDefault();
                    showMoreEventsModal(info.event.start);
                });
            }
        },

        eventDrop: function(info) {
            var eventId = info.event.id;
            var newStartDate = info.event.start;
            var newEndDate = info.event.end || newStartDate;
            var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
            var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

            $.ajax({
                method: 'post',
                url: `/schedule/${eventId}`,
                data: {
                    '_token': "{{ csrf_token() }}",
                    start_date: newStartDateUTC,
                    end_date: newEndDateUTC,
                },
                success: function() {
                    console.log('Event moved successfully.');
                },
                error: function(error) {
                    console.error('Error moving event:', error);
                }
            });
        },

        eventResize: function(info) {
            var eventId = info.event.id;
            var newEndDate = info.event.end;
            var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

            $.ajax({
                method: 'post',
                url: `/schedule/${eventId}/resize`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    end_date: newEndDateUTC
                },
                success: function() {
                    console.log('Event resized successfully.');
                },
                error: function(error) {
                    console.error('Error resizing event:', error);
                }
            });
        },


        eventClick: function(info) {
            const start_dateTimeString = info.event.extendedProps.appointment_start; // Example full date-time string
            const start_timePart = new Date(start_dateTimeString).toLocaleTimeString([], {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false
            });

            const end_dateTimeString = info.event.extendedProps.appointment_end; // Example full date-time string
            const end_timePart = new Date(end_dateTimeString).toLocaleTimeString([], {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false
            });

            const just_date = new Date(start_dateTimeString.replace(' ', 'T'));
            // Define options for formatting
            const options = {
                day: '2-digit',
                month: 'short',
                year: '2-digit'
            };
            // Format the date using toLocaleDateString
            const formattedDate = just_date.toLocaleDateString('en-GB', options);

            $('#head_eventDate').text(formattedDate || 'N/A');
            $('#head_eventTime').text(start_timePart + ' To ' + end_timePart);
            $('#head_eventAppointmentType').text(info.event.extendedProps.appointmentType || 'N/A');

            if (info.event.extendedProps.appointmentType == 'Video Consultation') {
                $(".bg-color").addClass("bg-tilt1");
                $(".bg-color").removeClass("bg-orange1");
                $(".bg-color").removeClass("bg-purpel1");

                $(".phone-consults").addClass("text-tilt1");
                $(".phone-consults").removeClass("text-orange1");
                $(".phone-consults").removeClass("text-purpel1");


            }

            if (info.event.extendedProps.appointmentType == 'Report Review') {
                $(".bg-color").addClass("bg-purpel1");
                $(".bg-color").removeClass("bg-orange1");
                $(".bg-color").removeClass("bg-tilt1");

                $(".phone-consults").addClass("text-purpel1");
                $(".phone-consults").removeClass("text-orange1");
                $(".phone-consults").removeClass("text-tilt1");
            }

            if (info.event.extendedProps.appointmentType == 'Phone Consultation') {
                $(".bg-color").addClass("bg-orange1");
                $(".bg-color").removeClass("bg-purpel1");
                $(".bg-color").removeClass("bg-tilt1");

                $(".phone-consults").addClass("text-orange1");
                $(".phone-consults").removeClass("text-purpel1");
                $(".phone-consults").removeClass("text-tilt1");
            }

            $('#eventDate').text(formattedDate || 'N/A');
            $('#eventStartEnd').text(start_timePart + ' To ' + end_timePart);
            $('#eventContactNumber').text(info.event.extendedProps.contactNumber || 'N/A');
            $('#eventCategory').text(info.event.extendedProps.category || 'N/A');
            $('#eventSymptoms').text(info.event.extendedProps.symptoms || 'N/A');
            $('#eventConsultationType').text(info.event.extendedProps.appointmentType || 'N/A');
            $('#eventAppointmentType').text(info.event.extendedProps.appointmentType || 'N/A');
            $('#eventInterests').text(info.event.extendedProps.interests || 'N/A');
            // $('#eventNumberOfJoints').text(info.event.extendedProps.number_of_joints || 'N/A');
            $('#eventNotes').text(info.event.extendedProps.notes || 'N/A');
            if (info.event.extendedProps.amount != '') {
                $('#eventAmount').text(' $' + info.event.extendedProps.amount);
            } else {
                $('#eventAmount').text('N/A');
            }


            // Safely handle report_file_names
            // var reportFiles = info.event.extendedProps.report_file_names;
            // if (Array.isArray(reportFiles) && reportFiles.length > 0) {
            //     $('#eventReportFiles').text(reportFiles.join(', '));
            // } else {
            //     $('#eventReportFiles').text('N/A');
            // }

            var appointmentType = info.event.extendedProps.appointmentType;
            var consultantLink = info.event.extendedProps.phone_meeting_link;
            if (appointmentType === 'Phone Consultation') {
               
                $('#eventMeetingLink').html('<span style="font-weight: 600"> Meeting Phone Number: <span id ="eventMeeting"></span> </span>')
                $('#eventMeeting').text('' + consultantLink);
            } else {
                const meetingLink = consultantLink || '#';
                $('#eventMeetingLink').attr('href', meetingLink);

                // Create the anchor element with text
                const anchorText = consultantLink ? 'Click Here to Join' : 'N/A';
                $('#eventMeetingLink').html(anchorText === 'N/A' ? 'N/A' : `<span style="font-weight: 600"> Meeting:</span> <a href="${meetingLink}" target="_blank">${anchorText}</a>`);
            }

            // $('#eventMeetingLink').attr('href', info.event.extendedProps.meeting_link || '#');
            // $('#eventMeetingLink').text(info.event.extendedProps.meeting_link ? 'View Link' : 'N/A');
            $('#eventStatus').text(info.event.extendedProps.status || 'N/A');

            // Populate patient details
            $('#patientName').text(info.event.extendedProps.name || 'N/A');
            $('#patientGender').text(info.event.extendedProps.gender || 'N/A');
            $('#patientAge').text(info.event.extendedProps.age + ' year' || 'N/A');
            $('#patientEmail').text(info.event.extendedProps.email || 'N/A');
            $('#patientContactNumber').text(info.event.extendedProps.contactNumber || 'N/A');
            $('#patientAlternateContactNumber').text(info.event.extendedProps.alternateContactNumber || 'N/A');

            $('#patientState').text(info.event.extendedProps.state.STATE_NAME || 'N/A');

            $('#patientCity').text(info.event.extendedProps.city.CITY || 'N/A');
            $('#patientInterests').text(info.event.extendedProps.interests || 'N/A');
            $('#patientAppointmentType').text(info.event.extendedProps.appointmentType || 'N/A');

            let medicalDocuments = info.event.extendedProps.medicalDocuments || 'N/A';

            if (medicalDocuments !== 'N/A') {
                let documentList = medicalDocuments.split(','); // Split the string into an array
                let html = ''; // Initialize an empty string to build the HTML content

                documentList.forEach(function(document) {
                    let trimmedDocument = document.trim(); // Trim any extra spaces
                    let filePath = `{{ URL('/') }}/public/patient_reports/${trimmedDocument}`; // Adjust the path as needed
                    html += `<a href="${filePath}" target="_blank">${trimmedDocument}</a><br>`; // Create an anchor tag
                });

                $('#patientMedicalDocuments').html(html); // Set the generated HTML to the span
            } else {
                $('#patientMedicalDocuments').text('N/A'); // If no documents, show 'N/A'
            }

            if (info.event.extendedProps.status == 'Confirmed') {
                $('#accept_appointment').text(info.event.extendedProps.status);
                $('#accept_appointment').removeClass("btn-primary");
                $('#accept_appointment').addClass("btn-success");
            }

            $('#eventModal').data('appointment-id', info.event.id).modal('show');

        }
    });



    calendar.render();

    // Define the modal display function
    function showMoreEventsModal(date) {
        // Get all events for the selected date
        const events = calendar.getEvents().filter(event =>
            event.start.toISOString().split('T')[0] === date.toISOString().split('T')[0]
        );

        // Create content for the modal
        let modalContent = events.map(event => `
            <p><b>${event.title}</b> ${event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
            </p>
            `).join('');

        // <i>${event.extendedProps.type}</i>

        // Display modal (replace with a custom modal in a real application)
        alert(modalContent); // Replace with modal logic
    }

    function filterAndDisplayEvents(searchKeywords) {
        $.ajax({
            method: 'GET',
            url: `{{ route('Doctor.getEvents') }}/search?title=${searchKeywords}`,
            success: function(response) {
                calendar.removeAllEvents();
                calendar.addEventSource(response);
            },
            error: function(error) {
                console.error('Error searching events:', error);
            }
        });
    }

    // Function to format time to 14-hour format with AM/PM

    function formatTime(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert to 24-hour format
        if (ampm === 'PM' && hours < 12) {
            hours += 12;
        }
        if (ampm === 'AM' && hours === 12) {
            hours = 0;
        }

        minutes = minutes < 10 ? '0' + minutes : minutes;
        return hours + ':' + minutes;
    }
</script>
@endsection