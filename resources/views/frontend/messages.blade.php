@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

<div class="back-to"><a href="#">Back to Past Appointment</a></div>
<div class="message-box">
  <div class="message-box-header"><div class="patients-list w-100 gap-4">
<div class="profile-menu"> 
          <a class="nav-link p-0" href="#">
            <div class="profile-pic">
                <img src="{{ url('/public/frontend/img/Ellipse 30.png') }}" alt="Profile Picture">
             </div> <span class="d-none d-md-flex">Dr. Alex</span>
         <!-- You can also use icon as follows: -->
           <!--  <i class="fas fa-user"></i> -->
          </a>
     </div>
          <ul class="list-one"><li><span>Date</span>14 July 2024</li><li><span>Time</span>4:00 PM </li><li><span>Symptoms</span>Joint Pain</li><li><span>Category </span>Follow-Up</li><li><span>Doctor</span>Dr. Alex</li></ul>
          </div></div>
  <div class="message-box-body">
    <div class="d-flex justify-content-end flex-column gap-1 mb-3 align-items-end"><div class="from-message">Lorem ipsum dolor sit amet</div><div>05:36 pm</div></div>

    <div class="d-flex justify-content-end flex-column gap-1 mb-3 align-items-start"><div class="to-message">  adipiscing elit</div><div>05:36 pm</div></div>

<div class="d-flex justify-content-end flex-column gap-1 mb-3 align-items-end"><div class="from-message">  adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div><div>05:36 pm</div></div>
<div class="d-flex justify-content-end flex-column gap-1 mb-3 align-items-end"><div class="from-message">Lorem ipsum dolor</div><div>05:36 pm</div></div>

  </div>
  <div class="message-box-footer">

<div class="conversation-box text-center"><p class="text-danger">This conversation is disabled. (You can chat only for 24 hours post appointment)</p><p>For booking a new appointment click on the button</p><a href="#" class="btn btn-orange">Book Now</a></div>
<div class="message-form">
<form><input type="text" name="" placeholder="Type here..."><button><img src="{{ url('/public/frontend/img/Vector(32).png') }}"></button></form>
</div>
</div>
</div>

</main>
</div>

<!-- Jquery -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
</body>
</html>
@endsection