<?php $userData = session('user',[]); 
  $lastSegment = Request::segment(count(Request::segments()));
  $secondLastSegment = request()->segment(count(request()->segments()) - 1);
 
        
  ?>
<!doctype html>
<html lang="en">
  <head>
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
    <meta charset="utf-8">
    <link rel="icon" href="{{ url('/') }}/public/img/favicon_original.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>:: SecondLookOrtho ::</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- Marqueefy CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@marqueefy/marqueefy@1.0.3/dist/css/marqueefy.min.css" >
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"  />


    <link href="{{ url('frontend/css/custom.css') }}" rel="stylesheet">
  </head>
    <body class="">
    <header id="masthead" class="fixed-top autohide">
  <div class="header-top">
    <div class="container">
      <div class="d-flex justify-content-between gap-3 align-items-center">
        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('frontend/img/brand.png') }}"></a>
        <div class="d-flex justify-content-between gap-3 align-items-center">
<div class="d-flex justify-content-between gap-3 align-items-center email-us"><span><img src="{{ url('frontend/img/Group 9521.png') }}"></span><div class="d-none d-md-flex flex-column"><a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a>Email us </div></div><button class="navbar-toggler p-0 border-0 d-flex d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
      </div>
    </div>
  </div>

  <div class="container">
      <nav class="navbar navbar-expand-md navbar-dark bg-dark"  id="navbar-example2" >
        
        <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto nav-desks">
        <li class="nav-item <?php if($lastSegment == ''){ echo 'active'; } ?>">
          <a class="nav-link" href="{{ url('/') }}">Home</a>
        </li>
      <!--  <li class="nav-item">
          <a class="nav-link" href="{{ url('/services') }}">Services</a>
        </li>-->
        <li class="nav-item <?php if($lastSegment && $lastSegment == 'How_It_Work'){ echo 'active'; }?>">
          <a class="nav-link" href="{{ url('/staticpage/How_It_Work') }}">How It Works</a>
        </li>
        <li class="nav-item <?php if($lastSegment && $lastSegment == 'About_Us'){ echo 'active'; }?>">
          <a class="nav-link {{ request()->routeIs('About_Us') ? 'active' : '' }}" href="{{ url('/staticpage/About_Us') }}">About Us</a>
        </li>
        <li class="nav-item dropdown <?php if($secondLastSegment && $secondLastSegment == 'second-opinion-on'){ echo 'active'; } ?>" id="mega-menus">
          <a class="nav-link dropdown-toggle" href="{{ url('/second-opinion') }}" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Second Opinion On
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'ankle_injury'}}">Ankle Injury</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'chronic_ankle_pain'}}">Chronic Ankle Pain</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'chronic_wrist_pain'}}">Chronic Wrist Pain</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'knee_injury'}}">Knee Injury</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'chronic_knee_pain'}}">Chronic Knee Pain</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'elbow_injury'}}">Elbow Injury</a></li>

            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'chronic_elbow_pain'}}">Chronic Elbow Pain</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'shoulder_injury'}}">Shoulder Injury</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'chronic_shoulder_pain'}}">Chronic Shoulder Pain</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'hip_injury'}}">Hip Injury</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'chronic_hip_pain'}}">Chronic Hip Pain</a></li>
            <li><a class="dropdown-item" href="{{ url('/second-opinion-on') }}/{{'osteoarthritis_treatment'}}">Osteoarthritis Treatment</a></li>
          </ul>
        </li>
        <li class="nav-item <?php if($lastSegment && $lastSegment == 'faq_homepage'){ echo 'active'; }?>">
          <a class="nav-link" href="{{ url('/faq_homepage') }}">FAQs</a>
         <!-- <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>-->
        </li>
        <li class="nav-item <?php if($lastSegment && $lastSegment == 'blog'){ echo 'active'; }?>">
          <a class="nav-link" href="{{ url('/blog') }}">Blogs</a>
        </li>
        <li class="nav-item <?php if($lastSegment && $lastSegment == 'add_contact_us'){ echo 'active'; }?>">
          <a class="nav-link" href="{{ url('/add_contact_us') }}">Contact Us</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto login-btn-bg">
       <li class="nav-item">
            @if($userData && !empty($userData))
                @if($userData['user_type'] == 'patient')
                    <a class="nav-link" href="{{ url('/login') }}"><img src="{{ url('frontend/img/p-login.png') }}"> {{ $userData['first_name'] }} {{ $userData['last_name'] }}</a>
                @else 
                    <a class="nav-link" href="{{ url('/login') }}"><img src="{{ url('frontend/img/p-login.png') }}"> {{ 'Dr. ' }}{{ $userData['first_name'] }} {{ $userData['last_name'] }}</a>
                @endif
            @else
              <a class="nav-link" href="{{ url('/login') }}"><img src="{{ url('frontend/img/p-login.png') }}"> Patient Login</a>
            @endif
        </li>
      </ul>
    </div>
  </div>
</nav>
</div>
         </header>