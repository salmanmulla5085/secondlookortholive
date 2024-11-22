@extends('layouts.app')

@section('content')
@include('layouts.navbars.topnav', ['title' => 'Manage Appointments'])

<style>
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

    .fc-event {
        /* border-color: rgb(78, 154, 6); 
            background-color: rgb(78, 154, 6);  */
        color: white;
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
</style>

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <!-- Other content can go here -->
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header pb-0 ">
                <h6>Booked Appointments :
                    <select id="doctor_id" name="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" autocomplete="doctor" style="display: inline;width:20%">
                        <option value="" selected>@lang('Select Doctor')...</option>
                        @foreach($result['doctors'] as $doctor)
                        <option value="{{ old('doctor', $doctor->id) }}">{{ old('doctor',  'Dr. '. ucfirst(strtolower($doctor->first_name))." ". ucfirst(strtolower($doctor->last_name))) }}</option>
                        @endforeach
                    </select>

                    <select id="selectedStatus" name="status" class="form-control @error('status') is-invalid @enderror" autocomplete="status" style="display: inline;width:20%">
                        <option value="" selected>@lang('Select Status')...</option>
                        <option value="In-process">In-Process</option>
                        <option value="Confirmed" selected>Confirmed</option>
                        <option value="Completed">Completed</option>
                        <option value="Expired">Expired</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Rejected">Rejected</option>
                        
                        
                    </select>
                </h6>



            </div>
            <div class="card-header pb-0 d-none">
                <h6>Booked Appointments:
                    <!-- <a href="{{ URL('add-schedule') }}" class="btn btn-success" style="margin-left:20px">{{__('Add New')}}</a> -->

                    <div class="col-12 col-md-3 col-lg-2">
                        @if(!empty($result['doctors']))
                        <div class="mb-3">
                            <select class="form-select form-control" id="doctor_id" name="doctor_id" required>
                                <option value="" disabled selected>Select doctor</option>
                                @foreach($result['doctors'] as $k => $v)
                                <option
                                    value="{{ $v->id }}">
                                    {{ 'Dr. '. $v->first_name }} {{ $v->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif



                    </div>

                </h6>

            </div>

            <div class="card-body px-4 pt-0 pb-2">
                <div id="calendar" style="width:100%; height:100vh"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<!-- Modal Structure -->
<!-- Modal Structure -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel" style="font-weight: bold;">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Appointment Details Section -->
                <div class="mb-3">
                    <h5>Appointment Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                           <p><strong>Doctor Name:</strong> <span id="eventDoctorName"></span></p>
                            <!--  <p><strong>Patient ID:</strong> <span id="eventPatientId"></span></p> -->
                            <p><strong>Date:</strong> <span id="eventDate"></span></p>
                            <p><strong>Time:</strong> <span id="eventStartEnd"></span></p>
                            <!-- <p><strong>Doctor:</strong> <span id="eventDoctor"></span></p> -->
                            <p><strong>Contact Number:</strong> <span id="eventContactNumber"></span></p>
                            <p><strong>Category:</strong> <span id="eventCategory"></span></p>
                            <p><strong>Symptoms:</strong> <span id="eventSymptoms"></span></p>
                            <p><strong>Appointment Type:</strong> <span id="eventAppointmentType"></span></p>
                            <p><strong>Joints :</strong> <span id="eventInterests"></span></p>
                        </div>
                        <div class="col-md-6">
                            <!-- <p><strong>Number of Joints:</strong> <span id="eventNumberOfJoints"></span></p> -->
                            <!-- <p><strong>Notes:</strong> <span id="eventNotes"></span></p> -->
                            <p><strong>Amount:</strong> <span id="eventAmount"></span></p>
                            <p><strong>Report Files:</strong> <span id="eventReportFiles"></span></p>
                            <p><strong>Meeting:</strong> <span id="eventMeetingLink"></span>
                            <!-- <a id="eventMeetingLink" href="#" target="_blank"></a><br> -->
                            <span id="eventMeetingPhoneNumber"> </span></p>
                            <p><strong>Status:</strong> <span id="eventStatus"></span></p>
                        </div>
                    </div>
                </div>

                <div class="mb-3 prescriptiondiv">
                    <h5>Prescription Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                           <p><strong>Doctor Name: </strong> <span id="eventPreDoctorName"></span></p>
                           <p><strong>Prescription: </strong><span id="eventPreContent"></span></p>
                           <p><strong>Prescription Files:</strong> <span id="eventPreFiles"></span></p>
                              </div>
                        <div class="col-md-6">
                            <!-- <p><strong>Number of Joints:</strong> <span id="eventNumberOfJoints"></span></p> -->
                            <!-- <p><strong>Notes:</strong> <span id="eventNotes"></span></p> -->
                        </div>
                    </div>
                </div>

                <!-- Patient Details Section -->
                <div class="mb-3">
                    <h5 style="font-weight: bold;">Patient Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <span id="patientName"></span></p>
                            <p><strong>Gender:</strong> <span id="patientGender"></span></p>
                            <p><strong>Age:</strong> <span id="patientAge"></span></p>
                            <p><strong>Email:</strong> <span id="patientEmail"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Contact Number:</strong> <span id="patientContactNumber"></span></p>
                            <p><strong>Alternate Contact Number:</strong> <span id="patientAlternateContactNumber"></span></p>

                            <p><strong>State:</strong> <span id="patientState"></span></p>
                            <p><strong>City:</strong> <span id="patientCity"></span></p>
                            <!-- <p><strong>Interests:</strong> <span id="patientInterests"></span></p>
                            <p><strong>Appointment Type:</strong> <span id="patientAppointmentType"></span></p>
                            <p><strong>Medical Documents:</strong> <span id="patientMedicalDocuments"></span></p> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
   function fileExists(url, callback) {
    fetch(url, { method: 'HEAD' })
        .then(function(response) {
            if (response.ok) {
                // File exists
                callback(true);
            } else {
                // File does not exist
                callback(false);
            }
        })
        .catch(function() {
            // Error or file does not exist
            callback(false);
        });
}

    document.addEventListener('DOMContentLoaded', function() {
        var doctorSelect = document.getElementById('doctor_id');
        var statusSelected = document.getElementById('selectedStatus');
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            timeZone: 'UTC',
            events: function(info, successCallback, failureCallback) {
                var doctorId = doctorSelect.value;
                var selectedStatus = statusSelected.value;

                // Build URL based on whether a doctor_id is selected
                // var url = `{{ url('/') }}/events${doctorId ? '?doctor_id=' + doctorId : ''}`;
                var url = `{{ url('/') }}/events${doctorId ? '?doctor_id=' + doctorId : ''}${doctorId ? '&status=' + selectedStatus : '?status=' + selectedStatus}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
            editable: true,
            dayMaxEvents: 4, // Show only 1 event, others will be collapsed into "+ more"

            eventContent: function(info) {
                
                var eventElement = document.createElement('div');
                // Report Review Video Consultation  Phone Consultation
                var consultation_type = info.event.extendedProps.appointmentType;

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
                eventElement.style.fontSize = "10px"; // Add 10px margin


                StartDateTimeString = info.event.startStr;
                
                var startTimePart = StartDateTimeString.substring(11, 16);
                
                console.log(startTimePart);

                EndDateTimeString = info.event.endStr;
                
                var endTimePart = EndDateTimeString.substring(11, 16);
                
                console.log(endTimePart);

                const FullTimeString = startTimePart + ' - ' + endTimePart;                                                               
                
                console.log(FullTimeString);
                
                var eventTitle = info.event.extendedProps.name+': '+FullTimeString;
                
                console.log('eventTitle :: '+ eventTitle);                
                eventElement.innerHTML = '<span class="js_loaded" style="cursor: pointer;"></span> ' + eventTitle;

                eventElement.querySelector('span').addEventListener('click', function() {
                    if (confirm("Are you sure you want to delete this event?")) {
                        var eventId = info.event.id;
                        $.ajax({
                            method: 'get',
                            url: '/schedule/delete/' + eventId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log('Event deleted successfully.');
                                calendar.refetchEvents(); // Refresh events after deletion
                            },
                            error: function(error) {
                                console.error('Error deleting event:', error);
                            }
                        });
                    }
                });
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
                /*
                const start_dateTimeString = info.event.start; // Example full date-time string                
                const start_timePart = new Date(start_dateTimeString).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                const end_dateTimeString = info.event.end; // Example full date-time string
                const end_timePart = new Date(end_dateTimeString).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                */

                
                const start_dateTimeString = info.event.start; // Example full date-time string
                // alert(start_dateTimeString);
                const startDate = new Date(start_dateTimeString);

                // Get the start time in "HH:mm" format without localization
                const start_timePart = `${String(startDate.getUTCHours()).padStart(2, '0')}:${String(startDate.getUTCMinutes()).padStart(2, '0')}`;
                // const start_timePart = `${String(startDate.getHours()).padStart(2, '0')}:${String(startDate.getMinutes()).padStart(2, '0')}`;
                // alert(start_timePart);

                const end_dateTimeString = info.event.end; // Example full date-time string
                const endDate = new Date(end_dateTimeString);

                // Get the end time in "HH:mm" format without localization
                const end_timePart = `${String(endDate.getUTCHours()).padStart(2, '0')}:${String(endDate.getMinutes()).padStart(2, '0')}`;


                // const just_date = new Date(start_dateTimeString.replace(' ', 'T'));                
                const just_date = new Date(start_dateTimeString);                
                // Define options for formatting
                const options = {
                    day: '2-digit',
                    month: 'short',
                    year: '2-digit'
                };                
                const formattedDate = just_date.toLocaleDateString('en-GB', options);

                // Populate modal with event details using direct properties
                $('#eventDate').text(formattedDate || 'N/A');
                $('#eventStartEnd').text(start_timePart + ' To ' + end_timePart);
                $('#eventDoctorId').text(info.event.extendedProps.doctor_id || 'N/A');
                $('#eventDoctorName').text(
                (info.event.extendedProps.user && info.event.extendedProps.user.first_name ? 'Dr. ' + info.event.extendedProps.user.first_name : 'N/A') + 
                ' ' + 
                (info.event.extendedProps.user && info.event.extendedProps.user.last_name ? info.event.extendedProps.user.last_name : '')
                );

                $('#eventPatientId').text(info.event.extendedProps.patient_id || 'N/A');
                $('#eventContactNumber').text(info.event.extendedProps.contactNumber || 'N/A');
                $('#eventCategory').text(info.event.extendedProps.category || 'N/A');
                $('#eventSymptoms').text(info.event.extendedProps.symptoms || 'N/A');
                $('#eventConsultationType').text(info.event.extendedProps.appointmentType || 'N/A');
                $('#eventAppointmentType').text(info.event.extendedProps.appointmentType || 'N/A');
                $('#eventInterests').text(info.event.extendedProps.interests || 'N/A');
                $('#eventNumberOfJoints').text(info.event.extendedProps.number_of_joints || 'N/A');
                // $('#eventNotes').text(info.event.extendedProps.notes || 'N/A');
                $('#eventAmount').text('$' + info.event.extendedProps.amount || 'N/A');
                

                // prescription
                $('#eventPreDoctorName').text(
                (info.event.extendedProps.user && info.event.extendedProps.user.first_name ? 'Dr. ' + info.event.extendedProps.user.first_name : 'N/A') + 
                ' ' + 
                (info.event.extendedProps.user && info.event.extendedProps.user.last_name ? info.event.extendedProps.user.last_name : '')
                );
                $('#eventPreContent').text(info.event.extendedProps.prec || 'N/A');

                var prescriptionFiles = info.event.extendedProps.upload_file1;
                console.log('pre'+prescriptionFiles);
 // Check if the reportFiles is a string and not empty
              if (prescriptionFiles && typeof prescriptionFiles === 'string') {
                    // Split the string into an array using commas
                    prescriptionFiles = prescriptionFiles.split(',');
                }
                if (Array.isArray(prescriptionFiles) && prescriptionFiles.length > 0) {
                    $('#eventPreFiles').html('');
                    // Loop through each file and create an anchor tag with a line break
                    prescriptionFiles.forEach(function(file) {
                        file = file.trim(); // Trim any extra spaces
                        let fileUrl = '{{ url('/') }}/public/patient_reports/' + file;
                        fileExists(fileUrl, function(exists) {
                         if (exists) {
                              console.log('File exists:', fileUrl);
                        var fileLink = `<a target="_blank" href="{{ url('/') }}/public/patient_reports/${file}">${file}</a><br>`;
                        $('#eventPreFiles').append(fileLink); // Append each link to the element
                         }
                        });
                    });
                } else {
                    $('#eventPreFiles').text('N/A');
                }
                // Safely handle report_file_names
                // var reportFiles = info.event.extendedProps.medicalDocuments;
                // if (Array.isArray(reportFiles) && reportFiles.length > 0) {
                //     $('#eventReportFiles').text(reportFiles.join(', '));
                // } else {
                //     $('#eventReportFiles').text('N/A');
                // }

                var reportFiles = info.event.extendedProps.medicalDocuments;

                // Check if the reportFiles is a string and not empty
                if (reportFiles && typeof reportFiles === 'string') {
                    // Split the string into an array using commas
                    reportFiles = reportFiles.split(',');
                }

                // Now check if it's an array and has files
                if (Array.isArray(reportFiles) && reportFiles.length > 0) {
                    $('#eventReportFiles').html('');
                    // Loop through each file and create an anchor tag with a line break
                    reportFiles.forEach(function(file) {
                        file = file.trim(); // Trim any extra spaces
                        let fileUrl = '{{ url('/') }}/public/patient_reports/' + file;
                        fileExists(fileUrl, function(exists) {
                         if (exists) {
                              console.log('File exists:', fileUrl);
                        var fileLink = `<a target="_blank" href="{{ url('/') }}/public/patient_reports/${file}">${file}</a><br>`;
                        $('#eventReportFiles').append(fileLink); // Append each link to the element
                         }
                        });
                    });
                } else {
                    $('#eventReportFiles').text('N/A');
                }

            // new code added for the  meeting details in the popup
                console.log(info.event.extendedProps.appointmentType); 
                 var appointmentType= info.event.extendedProps.appointmentType;
                 var consultantLink = info.event.extendedProps.phone_meeting_link;
                 if (appointmentType === 'Phone Consultation') {
                $('#eventMeetingLink').text('' + consultantLink);
            } else {
                const meetingLink = consultantLink|| '#';
                $('#eventMeetingLink').attr('href', meetingLink);

                // Create the anchor element with text
                const anchorText = consultantLink ? 'Click Here to Join' : 'N/A';
                $('#eventMeetingLink').html(anchorText === 'N/A' ? 'N/A' : `<a href="${meetingLink}" target="_blank">${anchorText}</a>`);
            }

                // old code
                // $('#eventMeetingLink').attr('href', info.event.extendedProps.phone_meeting_link || '#');
                // $('#eventMeetingLink').text(info.event.extendedProps.phone_meeting_link ? 'View Link' : 'N/A');
                $('#eventStatus').text(info.event.extendedProps.status || 'N/A');
                status_val = info.event.extendedProps.status;
                
                var status_val = info.event.extendedProps.status; // Example status value
console.log(status_val);
if (status_val == "Completed") {
   
    $('.prescriptiondiv').css('display', 'block'); // Show the div if status is completed
} else {
    $('.prescriptiondiv').css('display', 'none'); // Hide the div otherwise
}

                // Populate patient details
                $('#patientName').text(info.event.extendedProps.name || 'N/A');
                $('#patientGender').text(info.event.extendedProps.gender || 'N/A');
                $('#patientAge').text(info.event.extendedProps.age+ ' year' || 'N/A');
                $('#patientEmail').text(info.event.extendedProps.email || 'N/A');
                $('#patientContactNumber').text(info.event.extendedProps.contactNumber || 'N/A');
                $('#patientAlternateContactNumber').text(info.event.extendedProps.alternateContactNumber || 'N/A');
                $('#patientState').text(info.event.extendedProps.state && info.event.extendedProps.state.STATE_NAME || 'N/A');
                $('#patientCity').text(info.event.extendedProps.city && info.event.extendedProps.city.CITY || 'N/A');
                $('#patientInterests').text(info.event.extendedProps.interests || 'N/A');
                $('#patientAppointmentType').text(info.event.extendedProps.appointmentType || 'N/A');

                var medicalDocuments = info.event.extendedProps.medicalDocuments;
                if (Array.isArray(medicalDocuments) && medicalDocuments.length > 0) {
                    $('#patientMedicalDocuments').text(medicalDocuments.join(', '));
                } else {
                    $('#patientMedicalDocuments').text('N/A');
                }


                $('#eventModal').modal('show');
            }
        });


        calendar.render();

        doctorSelect.addEventListener('change', function() {
            calendar.refetchEvents();
        });
        
        statusSelected.addEventListener('change', function() {
            calendar.refetchEvents();
        });

        // Optionally, manually trigger refetch to load initial events
        calendar.refetchEvents();

    });

    // document.getElementById('searchButton').addEventListener('click', function() {
    //     var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
    //     filterAndDisplayEvents(searchKeywords);
    // });
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
            url: `{{ url('/') }}/events/search?title=${searchKeywords}`,
            success: function(response) {
                calendar.removeAllEvents();
                calendar.addEventSource(response);
            },
            error: function(error) {
                console.error('Error searching events:', error);
            }
        });
    }

    // document.getElementById('exportButton').addEventListener('click', function() 
    // {
    //     var events = calendar.getEvents().map(function(event) {
    //         return {
    //             title: event.title,
    //             start: event.start ? event.start.toISOString() : null,
    //             end: event.end ? event.end.toISOString() : null,
    //             color: event.backgroundColor,
    //         };
    //     });

    //     var wb = XLSX.utils.book_new();
    //     var ws = XLSX.utils.json_to_sheet(events);
    //     XLSX.utils.book_append_sheet(wb, ws, 'Events');
    //     var arrayBuffer = XLSX.write(wb, {
    //         bookType: 'xlsx',
    //         type: 'array'
    //     });
    //     var blob = new Blob([arrayBuffer], {
    //         type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    //     });
    //     var downloadLink = document.createElement('a');
    //     downloadLink.href = URL.createObjectURL(blob);
    //     downloadLink.download = 'events.xlsx';
    //     downloadLink.click();
    // });

    function formatTime(datetime) {

        

             return date("H:i",strtotime(datetime));

            // var hours = date.getHours();
            // var minutes = date.getMinutes();
            // var ampm = hours >= 12 ? 'PM' : 'AM';

            // // Convert to 24-hour format
            // if (ampm === 'PM' && hours < 12) {
            //     hours += 12;
            // }
            // if (ampm === 'AM' && hours === 12) {
            //     hours = 0;
            // }

            // minutes = minutes < 10 ? '0' + minutes : minutes;
            // return hours + ':' + minutes;
        }            
        
</script>
@endsection