@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php
// echo $result['record_type'];
// die;
?>

<div class="modal fade" id="enter_meeting_info_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter Phone Number OR Meeting Link</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="replyForm" action="{{ URL('/confirm_appointment_v3_post') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="confirm_appointment_id" id="confirm_appointment_id" value="">
          <input type="hidden" name="not_confirm_appointment" id="not_confirm_appointment" value="1">
          <div class="mb-3">
            <textarea class="form-control" id="phone_meeting_link" name="phone_meeting_link" placeholder="Enter phone number or meeting_link..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Confirm</button>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>

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


                                    @if (session('error'))
                                          <div class="alert alert-danger">
                                              {{ session('error') }}
                                          </div>
                                      @endif

                                      @if (session('warning'))
                                        <div class="alert alert-warning">
                                            {{ session('warning') }}
                                        </div>
                                    @endif

                                      
                                   
                                      
                                    
                             </div>  
                             


<div class="modal fade" id="app-rejectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span class="reason"></span> Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="reasonForm" action="" method="POST">
          @csrf
          <div class="mb-3">
            <textarea class="form-control" name="reason" placeholder="Enter your reason..." style="min-height:calc(3em + 4.75rem + 2px);" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary border-radius-0 ps-4 pe-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-orange border-radius-0 ps-4 pe-4">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
        
    <?php 
    if(!empty($result['appointments_booked']) && $result['appointments_booked']->isNotEmpty())
    {
    ?>        
        <div class="accordion row m-0" id="accordionExample">

        <?php
        $i =0;
        foreach($result['appointments_booked'] as $k=>$appointment)    
        {
            $i++;

            $Patient_sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id = $appointment->patient_id";
            $users = DB::select($Patient_sql);
            $PatientData = collect($users);               
            
            if($PatientData && count($PatientData) > 0){
                $patient_name = $PatientData[0]->first_name.' '.$PatientData[0]->last_name;
                $patient_phone_number = $PatientData[0]->phone_number;
            } else {
                $patient_name = '';
                $patient_phone_number = '';
            }

            $Doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id = $appointment->doctor_id";
            $Doctors = DB::select($Doctor_sql);
            $DoctorData = collect($Doctors);               
                        
            if($DoctorData && count($DoctorData) > 0){
                $doctor_name = 'Dr. '.$DoctorData[0]->first_name.' '.$DoctorData[0]->last_name;
            } else {
                $doctor_name = '';
            }

        ?>
            <div class="col-12 col-md-2 col-lg-1 p-0 d-flex align-items-stretch">
                <div class="pad-15 bg-white w-100 text-center d-flex flex-column gap-1"><span class="date">{{ \Carbon\Carbon::parse($appointment->start)->Format('j M  ') }}</span><span class="day">
                {{ \Carbon\Carbon::parse($appointment->start)->Format('D') }}
                </span>
                </div>
            </div>
            <div class="col-12 col-md-10 col-lg-11 p-0 d-flex align-items-stretch">
                <div class="pad-15 pe-0 pb-0 w-100">
                    <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
                        <h2 class="accordion-header">
                        <button class="accordion-button <?= ($i == 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
                            <div class="patients-list mb-0 me-3 w-100">
                                <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j M Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
                                <li><span>Category </span>{{ $appointment->category }}</li>
                                <li><span>Doctor</span>{{ $doctor_name }} </li></ul><span class="confirm">{{ $appointment->appointment_status }}</span>
                            </div>
                        </button>
                        </h2>
                        <div id="collapse_{{ $appointment->id }}" class="accordion-collapse collapse <?= ($i == 1) ? 'show' : '' ?>" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <!--div class="patients-list mb-4">
                            <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j F Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
                            <li><span>Category </span>{{ $appointment->category }}</li>
                            <li><span>Patient</span>{{ $appointment->patient_name }} </li></ul><span class="confirm">{{ $appointment->appointment_status }}</span>
                            </div-->
                            <div class="summries">
                            <div class="row">
                                <div class="col-12 col-md-9 col-lg-10">
                                <div class="row">
                                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                                        <span>Patient Name : </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $patient_name }} </div></div></div></div>
                                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                                        <span>Charges :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">${{ $appointment->amount }} </div></div>
                                        </div></div>
                                </div>
                                    <div class="row">
                                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>Consultation Type :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                                        <div class="mb-3">{{ $appointment->appointmentType }}</div></div></div></div>
                                        <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                                            <span>Contact Number :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $patient_phone_number }}</div></div></div></div>
                                </div>

                                <!--<div class="row">-->
                                <!--    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Joints of Interest : </span></div></div>-->
                                <!--    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->interests }}</div></div></div></div>-->
                                <!--</div>-->
                                <div class="row">
                                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Symptoms : </span></div></div>
                                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->symptoms }}</div></div></div></div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Note : </span></div></div>
                                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->notes }}</div></div></div></div>
                                </div> -->
                                    <div class="row">
                                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2">
                                        <div class="mb-0"><span>Documents/<br>Reports : </span></div></div><div class="col-12 col-md-9 col-lg-10"><div class="mb-0"><div class="d-inline-flex gap-1 flex-column">
                                            <?php
                                            if(!empty($appointment->medicalDocuments))
                                            {
                                                
                                            $reports = explode(",",$appointment->medicalDocuments);
                                            foreach($reports as $k=>$v)
                                            {
                                            ?>
                                            <a target="_blank" href="{{ url('/') }}/public/patient_reports/{{ $v }}">{{ $v }}</a>
                                            <?php
                                            }
                                            }
                                            ?>
                                            </div></div></div></div></div>
                                </div>
                                <div class="row mt-3 mb-3 mb-md-0">
                                    <div class="col-12 col-md-12">
                                        <div class="d-flex gap-2 align-items-center"><img src="{{ url('/public/frontend/img/Vector(41).png') }}" /> <a href="{{ url('/patient-history') }}/{{ Crypt::encrypt($appointment->id) }}/{{ Crypt::encrypt($appointment->patient_id) }}/{{ 'not_confirm_app' }}">View Patient History</a></div>
                                    </div>
                                </div>
                                </div>
                                <?php /*<div class="col-12 col-md-3 col-lg-2">
                                    <?php
                                    $booked_datetime = new DateTime($appointment->start);
                                    $now = new DateTime();
                                    // $booked_datetime = $booked_datetime->format("Y-m-d");
                                    // date("Y-m-d")
                                    $interval = new DateInterval('PT24H');
                                    $datetime_after_24hrs = $now->add($interval);                    

                                    if($appointment->appointment_status == "In-Process")
                                    {
                                    ?>
                                    <button type="button" data-appointment_status = "{{ $appointment->appointment_status }}" data-appointment-id = "{{ $appointment->id }}" class="accept_appointment btn btn-primary w-100 border-radius-0">Accept</button>                    
                                    <?php
                                    }
                                    
                                    if($booked_datetime > $datetime_after_24hrs)
                                    {
                                    ?>
                                    <!-- <a href="{{ URL('/reschedule-appointment') }}/{{ $appointment->id }}" class="btn btn-orange mb-3 w-100 border-radius-0">Reschedule</a> -->
                                    <!-- <a data-appointment-id = "{{ $appointment->id }}" class="accept_appointment btn btn-primary mb-3 w-100 border-radius-0">Accept</a> -->                                      
                                    <?php                    
                                    if($result['record_type'] != "rejected")
                                    {
                                        // echo $result['record_type'];
                                    ?>
                                    <a href="{{ URL('/doctor-cancel-appointment') }}/{{ $appointment->id }}" class="btn btn-outline-dark w-100 border-radius-0">Cancel</a>                   
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    }                    
                                    // $start_date = date('Y-m-d', strtotime($appointment->start));
                                    $end_datetime_plus_1day = date('Y-m-d H:i:s',strtotime($appointment->end.' +1 Day'));  
                                    $end_datetime_plus_1day = strtotime($end_datetime_plus_1day);

                                    
                                    if($result['record_type'] != "rejected" && $appointment->appointment_status == 'Confirmed')
                                    {
                                    //can chat only till 24 hrs after appointment not before appointment
                                    if(strtotime($appointment->end) < strtotime('now') && strtotime($appointment->end) <= $end_datetime_plus_1day)
                                        {
                                    ?>
                                        <!-- <form action="" method="POST">
                                            @csrf
                                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                                            <button type="submit" class="btn btn-primary">Chat Now with Patient</button>
                                        </form> -->
                                    <?php                    
                                        }
                                    }
                                    ?>
                                    </div>*/?>
                                    
                                    
                                <div class="col-12 col-md-3 col-lg-2">
                                    
                                        @if(!empty($result['doctors']))                                        
                                        <form method="POST" action="{{ URL('/confirm_appointment_v2_post') }}" enctype="multipart/form-data">
                                        @csrf
                                            <div class="mb-3">
                                                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}" required/>
                                                <input type="hidden" name="doctor_id" value="{{ $user->id }}" required/>
                                                
                                                <!-- <select class="form-select form-control" id="doctor_id" name="doctor_id" required>
                                                                    <option value="" disabled selected>Assign to doctor</option>                                                        
                                                                    @foreach($result['doctors'] as $k => $v)                                        
                                                                            <option                                             
                                                                            value="{{ $v->id }}"                                            
                                                                            >
                                                                            {{ $v->first_name }} {{ $v->last_name }}
                                                                            </option>                                        
                                                                    @endforeach
                                                </select> -->
                                            </div>

                                            <div class="mb-3">
                                                <button id="confirm_btn"
                                                    type="button" style="background-color: #02C4B7;" 
                                                    class="confirm_btn btn btn-orange w-100 border-radius-0" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#enter_meeting_info_Modal"
                                                        data-confirm_appointment_id="{{ $appointment->id }}"
                                                        data-appointmenttype="{{ $appointment->appointmentType }}" 
                                                        data-phone_no="{{ $patient_phone_number }}"
                                                        >
                                                        
                                                    Confirm
                                                </button>
                                            </div>
                                        </form>
                                        @endif
                                        <!-- <a href="{{ URL('/confirm-appointment-confirm-v2') }}/{{ $appointment->id }}" class="btn btn-orange mb-3 w-100 border-radius-0" onclick="return confirm('Do you want to confirm this appointment?')">Confirm</a> -->
                                        <!-- <a href="{{ URL('/confirm-appointment-v2/reject') }}/{{ $appointment->id }}" class="btn btn-outline-dark w-100 border-radius-0" onclick="return confirm('Do you want to reject this appointment?')">Reject</a> -->
                                        <a class="btn btn-outline-dark w-100 border-radius-0" data-bs-toggle="modal" data-bs-target="#app-rejectionModal" data-appointment-id="{{ $appointment->id }}" data-appointment-status="{{ 'reject' }}">Reject</a> 
                                </div>
                                
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>    
        <?php
        }
        ?>
        </div>
        <?php
    }
    else
    {       
    ?>        
    <div class="row m-0 No_Records_Found">
        <div class="col-12 col-md-12 col-lg-12 p-4 d-flex align-items-stretch">
            <h6>No Records Found </h6>
        </div>
    </div>
    <?php
    }
    ?>
</main>
</div>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->

</body>
</html>

<script>

$(document).ready(function() 
    {

        // $('.confirm_btn').on('click', function() {
            
        
            
        //     return true;
        // });

        $('#enter_meeting_info_Modal').on('show.bs.modal', function(event) 
        {
            
            var button = $(event.relatedTarget); // Button that triggered the modal
            var modal = $(this);
            
            confirm_appointment_id = button.data("confirm_appointment_id");
            $("#confirm_appointment_id").val(confirm_appointment_id);
            
            var confirm_appointmenttype = button.data('appointmenttype'); // Extract info from data-* attributes
            var phone_no = button.data('phone_no');
            
            if(confirm_appointmenttype == "Phone Consultation")
                {
                  $("#phone_meeting_link").val(phone_no);
                }

            // Make AJAX call to generate Zoom meeting link
            if(confirm_appointmenttype == "Video Consultation")
                {
                    // Clear the textarea initially
                    $("#phone_meeting_link").val(''); 
                    
                    // Disable the textarea and buttons
                    $("#phone_meeting_link").prop('disabled', true);
                    $(".modal-footer button").prop('disabled', true); // Disable the footer buttons
                    
                    // Add blur class to modal body (optional)
                    $(".modal-body").addClass('blur-content');
                    
                    $.ajax({
                            url: '{{ URL("/") }}/generate-zoom-meeting-link', // Laravel route for the controller
                            type: 'POST',
                            data: {
                                _token: $('input[name="_token"]').val(), // CSRF Token
                                appointment_id: confirm_appointment_id
                            },
                            success: function(response) {
                                // Set the response (Zoom meeting link) to the textarea
                                console.log(response);
                                if(response.error) {
                                    alert(response.error);
                                }
                                
                                if(response.join_url) {
                                    $("#phone_meeting_link").val(response.join_url); // Insert the Zoom join URL into the textarea
                                } else {
                                    alert('Failed to generate Zoom meeting link.');
                                }
                                
                                // Enable the textarea and buttons again
                                $("#phone_meeting_link").prop('disabled', false);
                                $(".modal-footer button").prop('disabled', false); // Enable the footer buttons
                    
                                // Remove blur class
                                $(".modal-body").removeClass('blur-content');
                            },
                            error: function(xhr) {
                                alert('Error generating Zoom meeting link.');
                                console.log('Error:', xhr);
                            }
                    });
                }
                
                modal.find('form').on('submit', function(event) {
                    // Show confirmation dialog
                    if (!confirm('Are you sure you want to proceed?')) {
                        // Prevent form submission if user cancels
                        event.preventDefault();
                    }
                });
        });


        $('#app-rejectionModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var appointmentId = button.data('appointment-id'); // Extract info from data-* attributes
            var status = button.data('appointment-status'); // Extract info from data-* attributes
            var modal = $(this);
            if(status == 'reject'){
                modal.find('.reason').html('Reject');

                // Update the form action URL
                var form = modal.find('form');
                var newActionUrl = "{{ URL('/confirm-appointment-v2/reject') }}/" + appointmentId;
                form.attr('action', newActionUrl);

            }
            // Add a submit event handler to the form
            modal.find('form').on('submit', function(event) {
                // Show confirmation dialog
                if (!confirm('Are you sure you want to proceed?')) {
                    // Prevent form submission if user cancels
                    event.preventDefault();
                }
            });
        });

        $('.accept_appointment').on('click', function() {
        
            
        const appointmentId = $(this).data('appointment-id'); // Assuming you set the appointment ID in the modal's data
        const appointment_status = $(this).data('appointment_status'); // Assuming you set the appointment ID in the modal's data
        const url = "{{ route('appointments.confirm') }}";
        const button = $(this); // Store a reference to the clicked button

        // if(appointment_status == 'Confirmed')
        //     {
        //     $('#accept_appointment').text(status);
        //     $('#accept_appointment').removeClass("btn-primary");
        //     $('#accept_appointment').addClass("btn-success");
        //     }

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
                    button.text('Confirmed');
                    // Change button class to 'btn-success'
                    button.removeClass('btn-primary').addClass('btn-success');
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

</script>
@endsection