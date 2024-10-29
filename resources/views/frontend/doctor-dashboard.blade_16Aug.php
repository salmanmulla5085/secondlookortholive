@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')
<?php
// echo $result['record_type'];
// die;
?>

<main id="main-page">
<header id="content-header">
<h1 class="title-font"><img src="{{ url('/public/frontend/img/Vector(27).png') }}"> Dashboard</h1> 
<div class="d-flex gap-4 align-items-center">
  <div class="search-box d-flex justify-content-space-between align-items-center"><img src="{{ url('/public/frontend/img/Vector(29).png') }}">  <input type="text" class="form-control" placeholder="Search"><button type="button"><img src="{{ url('/public/frontend/img/Vector(30).png') }}"></button></div>
  <div class="noti"><a href="{{ url('/notifications') }}"><img src="{{ url('/public/frontend/img/Vector(31).png') }}"></a></div>
  <div class="profiles">
    <ul class="navbar-nav profile-menu"> 
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="profile-pic">
                @php
                    // Retrieve the 'user' array from the session
                    $user = session('user',[]);
                @endphp

                @if ($user && isset($user['profile_photo']))
                    <img src="{{ url('/public/doctor_photos') }}/{{ $user['profile_photo'] }}" alt="Profile Photo">
                @else
                    <img src="{{ url('/public/frontend/mg/Ellipse 30.png') }}" alt="Profile Picture">
                @endif
            
             </div> <span class="d-none d-md-flex">
                        @if ($user && isset($user['first_name']))
                                {{ $user['first_name'] }}&nbsp;{{$user['last_name']}}
                        @endif  
                    </span>
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
                             
<div class="book-bg">
  <ul>
      <li class="<?= $result['record_type'] == 'upcoming' ? 'active' : '' ?>">
        <a href="{{ url('/') }}/doctor-dashboard/upcoming">Upcoming Appointments</a>
      </li>
      <li class="<?= $result['record_type'] == 'past' ? 'active' : '' ?>">
          <a href="{{ url('/') }}/doctor-dashboard/past">Past Appointments</a>
      </li>
  </ul>
  
  <!-- <a href="{{ url('/') }}/book_appointment" class="btn btn-book d-flex align-items-center gap-2"><img src="{{ url('/public/frontend/img/Layer 7.png') }}"> Book an Appointment</a> -->
  
</div>

<div class="accordion row m-0" id="accordionExample">
        
    <?php 
    if(!empty($result['appointments_booked']))
    {
    foreach($result['appointments_booked'] as $k=>$appointment)    
    {
    ?>

    <div class="col-12 col-md-2 col-lg-1 p-0 d-flex align-items-stretch"><div class="pad-15 bg-white w-100 text-center d-flex flex-column gap-1"><span class="date">{{ \Carbon\Carbon::parse($appointment->start)->Format('j F') }}</span><span class="day">
    {{ \Carbon\Carbon::parse($appointment->start)->Format('D') }}
    </span></div>
    </div>
    <div class="col-12 col-md-10 col-lg-11 p-0 d-flex align-items-stretch">
    <div class="pad-15 pe-0 pb-0 w-100">
    <div class="accordion-item mb-0 mb-md-0 mb-lg-0">
        <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $appointment->id }}" aria-expanded="false" aria-controls="collapse_{{ $appointment->id }}">
            {{ $appointment->description }}
        </button>
        </h2>
        <div id="collapse_{{ $appointment->id }}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <div class="patients-list mb-4">
            <ul class="list-one"><li><span>Date</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('j F Y') }}</li><li><span>Time</span>{{ \Carbon\Carbon::parse($appointment->start)->Format('G:i') }}</li>
            <li><span>Symptoms</span>{{ $appointment->symptoms }}</li><li><span>Category </span>{{ $appointment->category }}</li>
            <li><span>Patient</span>{{ $appointment->patient_name }} </li></ul><span class="confirm">{{ $appointment->appointment_status }}</span>
            </div>
            <div class="summries">
            <div class="row">
                <div class="col-12 col-md-9 col-lg-10">
                <div class="row">
                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                        <span>Patient Name : </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $appointment->patient_name }} </div></div></div></div>
                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                        <span>Charges :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">${{ $appointment->amount }} </div></div>
                        </div></div>
                </div>
                    <div class="row">
                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>Appointment Type :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                        <div class="mb-3">{{ $appointment->appointmentType }}</div></div></div></div>
                        <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                            <span>Contact Number :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $appointment->patient_phone_number }}</div></div></div></div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3"><span>City :  </span></div></div><div class="col-12 col-md-7 col-lg-8">
                        <div class="mb-3">{{ $appointment->city }}</div></div></div></div>
                        <div class="col-12 col-md-6"><div class="row"><div class="col-12 col-md-5 col-lg-4"><div class="mb-3">
                            <span>State :  </span></div></div><div class="col-12 col-md-7 col-lg-8"><div class="mb-3">{{ $appointment->state }}</div></div></div></div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Joints of Interest : </span></div></div>
                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->interests }}</div></div></div></div>
                </div>
                    <div class="row">
                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2"><div class="mb-3"><span>Note : </span></div></div>
                    <div class="col-12 col-md-9 col-lg-10"><div class="mb-3">{{ $appointment->notes }}</div></div></div></div>
                </div>
                    <div class="row">
                    <div class="col-12 col-md-12"><div class="row"><div class="col-12 col-md-3 col-lg-2">
                        <div class="mb-0"><span>Documents/Reports : </span></div></div><div class="col-12 col-md-9 col-lg-10"><div class="mb-0"><div class="d-inline-flex gap-1 flex-column">
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
                </div>
                <div class="col-12 col-md-3 col-lg-2">
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

                    <a href="{{ URL('/doctor-cancel-appointment') }}/{{ $appointment->id }}" class="btn btn-outline-dark w-100 border-radius-0">Cancel</a>
                    <?php
                    }
                    ?>
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
}
?>
</div>



</main>
</div>

<!-- Jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>

<script>

$(document).ready(function() 
    {
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