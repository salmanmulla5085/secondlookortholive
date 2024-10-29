<?php 
  $userData = session('user', []);  
  $lastSegment = Request::segment(count(Request::segments()));
  $GetNotReply = _get_not_replies_report_reviews($userData['id']);
  $GetNotMsgCount = _get_notification_message_count($userData['id']);
  $GetMsgCount = _get_message_count($userData['id']);
?>

<!doctype html>
<html lang="en">
  <head>

  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  @section('scripts')
  <script>
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      setInterval(function() {
    $.get('/refresh-csrf').done(function(data) {
        $('meta[name="csrf-token"]').attr('content', data); // Update the meta tag
    });
}, 10 * 60 * 1000); // Refresh every 10 minutes

  </script>
  @endsection
    <link rel="icon" href="{{ url('/') }}/public/img/favicon_original.png" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>:: SecondLookOrtho ::</title>
        <!-- Jquery -->
    
    <!-- Jquery validation -->
    <!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script> -->
    
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"  />
    <link href="{{ url('/public/frontend/css/dashboard.css') }}" rel="stylesheet">
    <!-- for date_add -->
    <!-- jQuery Library -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <script>
      $(document).ready(function(){
        $('.accordion-button').on('click', function () {
            // Wait for the accordion to expand before scrolling
            setTimeout(() => {
                // Find the accordion item that was clicked
                let $accordionItem = $(this).closest('.accordion-item');

                // Use scrollIntoView to scroll the expanded accordion item to the center
                $accordionItem[0].scrollIntoView({
                    behavior: 'smooth', // Smooth scroll
                    block: 'center' // Scroll to the center of the screen
                });
            }, 300); // Adjust delay if needed
        });
      });
    </script>
  </head>
    <body class="">
<div id="dash-wrapper">
  <header>
  <!--- desktop use only -->
  @php
    // Retrieve the 'user' array from the session
    if(!empty($userData['id']))
    {
      $userId = $userData['id'];
      if(!empty($userId)) {
          $User_sql = "SELECT * FROM dbl_users where id = $userId";
          $users = DB::select($User_sql);
          if(!empty($users)){
              $user = $users[0];
          }
      }
    }
    @endphp

  <style>
    .icon_img{
      height: 22px !important;
      width: 22px !important;
    }

    .active_clr {
      color: #02C4B7 !important;
    }
  </style>
<script>
    const UserType = '<?php echo $userData['user_type']; ?>';

    let inactivityPatientTime = function () {
        let time;
        const logoutAfter =  15 * 60 * 1000; // 15 minutes in milliseconds

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onscroll = resetTimer;
        document.onclick = resetTimer;

        function logout_patient() {
            window.location.href = '{{ URL("/logout") }}';
        }

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(logout_patient, logoutAfter);
        }
    };

    let inactivityDoctorTime = function () {
        let time;
        const logoutAfter = 15 * 60 * 1000; // 15 minutes in milliseconds

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onscroll = resetTimer;
        document.onclick = resetTimer;

        function logout() {
            window.location.href = '{{ URL("/doctor_logout") }}'; // Replace with your logout route
        }

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(logout, logoutAfter);
        }
    };

    if(UserType != '' && UserType == 'doctor'){
      inactivityDoctorTime();
    } 
    else if(UserType != '' && UserType == 'patient') {
      inactivityPatientTime();
    }
    
</script>

<div class="offcanvas d-none d-md-none d-lg-none d-xl-flex" id="sidebar-desktop">
    <div class="offcanvas-header">
        <div class="offcanvas-title">
            @php
                $logo_url = (isset($user->user_type) && $user->user_type === 'patient') ? url('/patient-dashboard') : url('/doctor-dashboard');
            @endphp 
            <a href="{{ $logo_url }}"><img src="{{ url('/public/frontend/img/brand.png') }}"></a>
        </div>       
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 top-navs mb-3">
        @if (isset($user->user_type) && $user->user_type === 'patient')
        <!-- HTML for patient user type -->
        <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'patient.dashboard' ? 'active active_clr' : '' }}" aria-current="page" href="{{ url('/patient-dashboard') }}">
            <img src="{{ url('/public/frontend/img/Group(40).png') }}" alt="Dashboard">
            Dashboard
        </a>
        </li>
        @elseif (isset($user->user_type) && $user->user_type === 'doctor')
        <!-- HTML for doctor user type -->
        <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'doctor.dashboard' ? 'active active_clr' : '' }}" aria-current="page" href="{{ url('/doctor-dashboard') }}">
            <img src="{{ url('/public/frontend/img/Group(40).png') }}" alt="Dashboard">
            Dashboard
        </a>
        </li>
          @if (isset($user->admin) && $user->admin == 1)
            <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'doctor.not_confirmed_appintments' ? 'active active_clr' : '' }}" aria-current="page" href="{{ url('/not-confirmed-appintments') }}">
                <img src="{{ url('/public/frontend/img/Group 9956.png') }}" alt="Not Confirmed">
                Not Confirmed Appointments
            </a>
            </li>
          @endif
        @endif

            <li class="nav-item">
              <a class="nav-link {{ Route::currentRouteName() == 'new-messages' ? 'active active_clr' : '' }}" href="{{ url('/new-messages') }}"><?php if($GetMsgCount && count($GetMsgCount) > 0){ ?><span class="msg_flag"></span> <?php } ?><img src="{{ url('/public/frontend/img/patient_icon_02.png') }}">Messages</a>
            </li>

            @if (isset($user->user_type) && $user->user_type === 'patient')
            <li class="nav-item">
              <a class="nav-link {{ Route::currentRouteName() == 'patient.report-reviews' ? 'active active_clr' : '' }}" href="{{ url('/patient-report-reviews') }}">
                <img src="{{ url('/public/frontend/img/patient_icon_01.png') }}">Report Reviews</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::currentRouteName() == 'plans' ? 'active active_clr' : '' }}" href="{{ url('/plans') }}"><img src="{{ url('/public/frontend/img/patient_icon_03.png') }}">Consultation Plans</a>
            </li>   
            
            <li class="nav-item">
              <a class="nav-link {{ Route::currentRouteName() == 'patient_payments_history' ? 'active active_clr' : '' }}" href="{{ url('/patient_payments_history') }}">
              <img src="{{ url('/public/frontend/img/Group 9954.png') }}">Payments</a>
            </li>
            @endif
            
            
            <li class="nav-item">
              <a class="nav-link {{ Route::currentRouteName() == 'settings' ? 'active active_clr' : '' }}" href="{{ url('/settings') }}"><img src="{{ url('/public/frontend/img/Group 9677.png') }}">Settings</a>
            </li>

           

        @if (isset($user->user_type) && $user->user_type === 'doctor')
        <!-- HTML for doctor user type -->
        <li class="nav-item">
              <a class="nav-link {{ Route::currentRouteName() == 'doctor.report-reviews' ? 'active active_clr' : '' }}" href="{{ url('/doctor-report-reviews') }}"><img src="{{ url('/public/frontend/img/patient_icon_01.png') }}">Report Reviews<?php if($GetNotReply && count($GetNotReply) > 0){ ?><span class="disc_dup"></span> <?php } ?></a>
        </li>

        <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'DoctorAvailableScheduleSlots' ? 'active active_clr' : '' }}" aria-current="page" href="{{ url('/Doctor-AvailableScheduleSlots') }}">
            <img src="{{ url('/public/frontend/img/Group 9978.png') }}" alt="Dashboard">
            My Availability
        </a>
        </li>

        <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'Doctor.schedule' ? 'active active_clr' : '' }}" aria-current="page" href="{{ route('Doctor.schedule') }}">
            <img src="{{ url('/public/frontend/img/Group(45).png') }}" alt="Dashboard">
            My Calendar
        </a>
        </li>

        @endif           
            
          </ul>
          <div class="divider"></div>
          <ul class="navbar-nav justify-content-end flex-grow-1 privacy-nav mt-3">
            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/') }}/privacy_policy">Privacy Policy</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/') }}/term_condition">Terms & Conditions</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ URL('/') }}/Compliance">HIPPA Compliance</a>
            </li>
          </ul>
    </div>
    <div class="offcanvas-footer">
    @if(isset($user->user_type) && $user->user_type === 'patient')
      <a href="{{ url('/logout') }}"><img src="{{ url('/public/frontend/img/Group 9684.png') }}">Logout</a>
    @elseif(isset($user->user_type) && $user->user_type === 'doctor')
      <a href="{{ url('/doctor_logout') }}"><img src="{{ url('/public/frontend/img/Group 9684.png') }}">Logout</a>
    @endif
    </div>
</div>
<!-- end -->
  <!--- Mobile use only -->
<div class="offcanvas offcanvas-start d-flex d-md-flex d-lg-flex d-xl-none" id="sidebar-mobiles">
    <div class="offcanvas-header">
        <div class="offcanvas-title">
            <a href="#"><img src="{{ url('/public/frontend/img/brand.png') }}"></a>
        </div>       
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 top-navs mb-3">
            
            @if (isset($user->user_type) && $user->user_type === 'patient')
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="{{ url('/patient-dashboard') }}"><img src="{{ url('/public/frontend/img/Group(40).png') }}">  Dashboard</a>
            </li>
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link" href="{{ url('/messages-list') }}"><img src="{{ url('/public/frontend/img/Group(40).png') }}">Messages</a>-->
            <!--</li>-->
            
            @elseif (isset($user->user_type) && $user->user_type === 'doctor')
            <!-- HTML for doctor user type -->
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ url('/doctor-dashboard') }}">
                <img src="{{ url('/public/frontend/img/Group(40).png') }}" alt="Dashboard">
                Dashboard
            </a>
            </li>
            @endif
            
            @if (isset($user->admin) && $user->admin == 1)
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ url('/not-confirmed-appintments') }}">
                    <img src="{{ url('/public/frontend/img/Group 9956.png') }}" alt="Not Confirmed">
                    Not Confirmed Appointments
                </a>
                </li>
            @endif
            
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/new-messages') }}"><?php if($GetMsgCount && count($GetMsgCount) > 0){ ?><span class="msg_flag"></span> <?php } ?><img src="{{ url('/public/frontend/img/patient_icon_02.png') }}">Messages</a>
            </li>
            
            @if (isset($user->user_type) && $user->user_type === 'patient')
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/patient-report-reviews') }}">
                <img src="{{ url('/public/frontend/img/patient_icon_01.png') }}">Report Reviews</a>
            </li>  
            
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/plans') }}"><img src="{{ url('/public/frontend/img/Group(40).png') }}">Consultation Plans</a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/patient_payments_history') }}">
              <img src="{{ url('/public/frontend/img/Group 9954.png') }}">Payments</a>
            </li>
            
            @endif
            
            <li class="nav-item">
              <a class="nav-link" href="{{ url('/settings') }}"><img src="{{ url('/public/frontend/img/Group(40).png') }}">Settings</a>
            </li>
            
            @if (isset($user->user_type) && $user->user_type === 'doctor')
                <!-- HTML for doctor user type -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/doctor-report-reviews') }}"><img src="{{ url('/public/frontend/img/patient_icon_01.png') }}">Report Reviews<?php if($GetNotReply && count($GetNotReply) > 0){ ?><span class="disc_dup"></span> <?php } ?>
                    </a>
                </li>
        
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ url('/Doctor-AvailableScheduleSlots') }}">
                        <img src="{{ url('/public/frontend/img/Group 9978.png') }}" alt="Dashboard">
                        My Availability
                    </a>
                </li>
        
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('Doctor.schedule') }}">
                        <img src="{{ url('/public/frontend/img/Group(45).png') }}" alt="Dashboard">
                        My Calendar
                    </a>
                </li>
            @endif 
            
          </ul>
          <div class="divider"></div>
          <ul class="navbar-nav justify-content-end flex-grow-1 privacy-nav mt-3">
            <li class="nav-item">
              <a class="nav-link" href="#">Privacy Policy</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Terms & Conditions</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#">HIPPA Compliance</a>
            </li>
          </ul>
    </div>
    <div class="offcanvas-footer">
    @if(isset($user->user_type) && $user->user_type === 'patient')
        <a href="{{ url('/logout') }}"><img src="{{ url('/public/frontend/img/Group 9684.png') }}">Logout</a>
    @elseif(isset($user->user_type) && $user->user_type === 'doctor')
      <a href="{{ url('/doctor_logout') }}"><img src="{{ url('/public/frontend/img/Group 9684.png') }}">Logout</a>
    @endif
    </div>
</div>

<main id="main-page">
    <header id="content-header">
        <h1 class="title-font">
          <?php if(isset($icon) && $icon != ''){ ?>
            <img class="icon_img" src="{{ url('/public/frontend/img/') }}{{ '/' }}{{ $icon }}">
          <?php } else { ?>
            <img class="icon_img" src="{{ url('/public/frontend/img/Group(40).png') }}">
          <?php } ?>
          <span id="PageName"><?php if(isset($PageName) && !empty($PageName)){ echo $PageName; } elseif($lastSegment == 'Doctor-add-AvailableScheduleSlots') { echo 'Available Time Slot'; } else { echo '';}?>
          </span>
        </h1> 
        <div class="d-flex gap-4 align-items-center justify-content-end">
            @if (isset($user->user_type) && $user->user_type === 'patient')
                <form id="searchForm" action="{{ url('/') }}/book_appointment" method="POST">
                    @csrf 
                    <div class="search-box d-flex justify-content-space-between align-items-center"><img src="{{ url('/public/frontend/img/Vector(29).png') }}">  
                        <input type="text" class="form-control" name="search" value="<?php if(isset($SearchString) && !empty($SearchString)){ echo $SearchString; } ?>" placeholder="Search" required>
                        <button type="submit"><img src="{{ url('/public/frontend/img/Vector(30).png') }}"></button>
                    </div>
                </form>
            @endif
            <div class="noti"><a href="{{ url('/notifications') }}"><?php if($GetNotMsgCount && count($GetNotMsgCount) > 0){ ?><span class="noti_flag"></span> <?php } ?><img src="{{ url('/public/frontend/img/Vector(31).png') }}"></a></div>
            <div class="profiles">
                <ul class="navbar-nav profile-menu"> 
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic">
                            @if (!empty($user->profile_photo) && $user->user_type === 'patient')
                                <img src="{{ url('/public/patient_photos/') }}/{{ $user->profile_photo }}" alt="Profile Picture">
                            @elseif(!empty($user->profile_photo) && $user->user_type === 'doctor')
                                <img src="{{ url('/public/doctor_photos/') }}/{{ $user->profile_photo }}" alt="Profile Picture">
                            @else
                                <img src="{{ url('/public/patient_photos/doctor.jpg') }}" alt="Profile Picture">
                            @endif
                            </div> 
                            @if (!empty($user) && $user->user_type === 'doctor')
                                <span class="d-none d-md-flex">
                                  <?= (!empty($user->first_name)) ? 'Dr. '.$user->first_name : ""; ?>
                                  </span>
                            @else
                                <span class="d-none d-md-flex">                                  
                                  <?= (!empty($user->first_name)) ? $user->first_name.' '.$user->last_name : ""; ?>
                                </span>
                            @endif
                     <!-- You can also use icon as follows: -->
                       <!--  <i class="fas fa-user"></i> -->
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ url('/acc-update') }}"><i class="fas fa-sliders-h fa-fw"></i> My Account</a></li>
                        <!--<li><a class="dropdown-item" href="#"><i class="fas fa-cog fa-fw"></i> Settings</a></li>-->
                        <li><hr class="dropdown-divider"></li>
                        @if(isset($user->user_type) && $user->user_type === 'patient')
                          <li><a class="dropdown-item" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a></li>
                        @elseif(isset($user->user_type) && $user->user_type === 'doctor')
                          <li><a class="dropdown-item" href="{{ url('/doctor_logout') }}"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a></li>
                        @endif
                      </ul>
                    </li>
                 </ul>
            </div> 
            <button class="navbar-toggler btn d-flex d-md-flex d-lg-flex d-xl-none p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-mobiles">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>