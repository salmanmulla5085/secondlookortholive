<footer id="main-footer" class="pt-4 pb-0">
  <div class="container">
    <div class="rows1">
      <div class="cols terms1"><a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('frontend/img/brand.png') }}"></a>
        <ul class="single-list mt-4 mb-4 mb-md-4 mb-lg-0 ">
          <li><a href="{{ url('/term_condition')}}">Terms & Conditions</a></li>
          <li><a href="{{ url('/privacy_policy')}}">Privacy Policies</a></li>
          <li><a href="#">Documentation</a></li>
        </ul>
      </div>
      <div class="cols quick-links">
        <h4 class="mb-0">Quick Links</h4>
        <ul class="single-list mt-3 mb-4 mb-md-4 mb-lg-0 ">
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('/staticpage/How_It_Work') }}">How it works</a></li>
          <li><a href="{{ url('/staticpage/About_Us') }}">About us</a></li>
          <li><a href="{{ url('/blog') }}">Blogs</a></li>
          <!--<li><a href="{{ url('/second-opinion') }}">Second opinion on</a></li>-->
          <li><a href="{{ url('/faq_homepage') }}">FAQs</a></li>
          <li><a href="{{ url('/add_contact_us') }}">Contact us</a></li>
        </ul>
      </div>
      <div class="cols">
        <h4 class="mb-0">Second Opinion on</h4>
        <ul class="double-list mt-3 mb-4 mb-md-4 mb-lg-0 ">
          <li><a href="{{ url('/second-opinion-on') }}/{{'ankle_injury'}}">Ankle Injury</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'elbow_injury'}}">Elbow injury</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'chronic_ankle_pain'}}">Chronic Ankle Pain</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'chronic_elbow_pain'}}">Chronic Elbow Pain</a></li>
          <!-- <li><a href="{{ url('/second-opinion-on') }}/{{'wrist_injury'}}">Wrist Injury</a></li> -->

          <li><a href="{{ url('/second-opinion-on') }}/{{'shoulder_injury'}}">shoulder injury</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'chronic_wrist_pain'}}">Chronic wrist pain</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'chronic_shoulder_pain'}}">chronic shoulder pain</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'knee_injury'}}">Knee injury</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'hip_injury'}}">hip injury</a></li>

          <li><a href="{{ url('/second-opinion-on') }}/{{'chronic_knee_pain'}}">chronic knee pain</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'chronic_hip_pain'}}">chronic hip pain</a></li>
          <li><a href="{{ url('/second-opinion-on') }}/{{'osteoarthritis_treatment'}}">Osteoarthritis Treatment</a></li>
        </ul>
      </div>
      <div class="cols last-cols">
        <?php 
         $sql = "SELECT * FROM static_page_contents where static_page_id = 1 order by id desc";
         $staticPagemore = DB::select($sql);
        //  dd( $staticPageData[0]->more_info);
        ?>
        <h4 class="mb-3">For More info</h4>
        <?php /*if(isset($staticPageData[0]['more_info'])){?>
        <p>{{ $staticPageData[0]['more_info'] }} </p>
        <?php }else{?>
        <p></p>
        <?php } */?>
        <?php /*$lastSegment = last(explode('/', url()->current()));
        if ($lastSegment == 'doctor') { ?>
          <a href="{{ url('/login') }}" class="btn btn-card"><img src="{{ url('frontend/img/p-login.png') }}"> Patient Login</a>
        <?php } else {*/ ?>
          <a href="{{ url('/login/doctor') }}" class="btn btn-card"><img src="{{ url('frontend/img/p-login.png') }}"> Doctor Login</a>
        <?php /*}*/ ?>
      </div>
    </div>
  </div>
  <div class="copy mt-4">
    <div class="container" style="font-size:12px;">Copyright © 2024 SecondLook Ortho | Designed & Developed by <a href="https://www.aviontechnology.net/" target="_blank">Avion Techology, Inc.</a></div>
  </div>
</footer>

<!-- Jquery -->



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Marqueefy JS -->
<script src="https://cdn.jsdelivr.net/npm/@marqueefy/marqueefy@1.0.3/dist/js/marqueefy.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script> -->
<script src="{{ url('/public/assets/js/validation.js') }}"></script>


<script type="text/javascript">
  // swiper

  var swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
  });
  // masthead auto hide
//   document.addEventListener("DOMContentLoaded", function() {

//     el_autohide = document.querySelector('.autohide');

//     // add padding-top to bady (if necessary)
//     navbar_height = document.querySelector('.header-top').offsetHeight;
//     document.body.style.paddingTop = navbar_height + 'px';

//     if (el_autohide) {

//       var last_scroll_top = 0;
//       window.addEventListener('scroll', function() {
//         let scroll_top = window.scrollY;
//         if (scroll_top < last_scroll_top) {
//           el_autohide.classList.remove('scrolled-down');
//           el_autohide.classList.add('scrolled-up');
//         } else {
//           el_autohide.classList.remove('scrolled-up');
//           el_autohide.classList.add('scrolled-down');
//         }
//         last_scroll_top = scroll_top;

//       });
//       // window.addEventListener
//     }
//     // if
//   });
  // DOMContentLoaded  end

  // Initialize Marqueefy
  const marqueefyList = Array.prototype.slice.call(document.querySelectorAll('.marqueefy'))
  const marqueefyInstances = marqueefyList.map(m => {
    return new marqueefy.Marqueefy(m)
  })
</script>
</body>

</html>