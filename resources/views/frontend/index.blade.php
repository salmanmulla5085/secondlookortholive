@extends('frontend.layouts.main')
@section('main.container')
<main id="page-main">
  <section class="section-banners d-flex justify-content-center align-items-center">
    <div class="container text-center">
      <h2 class="mb-0">Online</h2>
      <h1>Orthopedic Consultation</h1>
      <p>Better. Faster. Cheaper.</p>
    </div>
  </section>

  <section class="main-sections pt-5 pb-5">
    <div class="container">
      <div class="appointmtnts">
        <ul>
          <li><i><img src="{{ url('frontend/img/Group(28).png') }}"></i>
            <h4>{{ $staticPageData[0]['section_heading1'] }}</h4>
            <div class="appoint-contents">
              <p>{{ $staticPageData[0]['section_short_desc1'] }} </p>
            </div>
          </li>
          <li><i><img src="{{ url('frontend/img/Group(29).png') }}"></i>
          <h4>{{ $staticPageData[0]['section_heading2'] }}</h4>
            <div class="appoint-contents">
              <p>{{ $staticPageData[0]['section_short_desc2'] }}</p>
            </div>
          </li>
          <li><i><img src="{{ url('frontend/img/Group(30).png') }}"></i>
          <h4>{{ $staticPageData[0]['section_heading3'] }}</h4>
            <div class="appoint-contents">
              <p>{{ $staticPageData[0]['section_short_desc3'] }}</p>
             </div>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <section class="find-doctors pt-5 pb-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-md-6 col-lg-6">
          <h2>{{ $staticPageData[0]['section_heading4'] }}</h2>
          <p>{!! $staticPageData[0]['section_long_desc1'] !!}</p>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
          <div class="opinion-card-sec">
            <div class="opinion-left">
              <div class="opinoion-img"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image1'] }}"></div>
            </div>
            <div class="opinoion-content">
              <div class="phone-consult">
                <div><img src="{{ url('frontend/img/Vector(24).png') }}">PHONE Consultation</div>
              </div>
              <div class="video-consult">
                <div><img src="{{ url('frontend/img/Group 9617.png') }}">Video Consultation</div>
              </div>
              <div class="report-consult">
                <div><img src="{{ url('frontend/img/Layer 4.png') }}">Report Analysis</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <section class="process pt-5 pb-5">
    <div class="container">
      <h2 class="text-center h2-big mb-4">Our Process</h2>
      <ul class="process-steps">
        <li>
          <div class="round1">
            <div>1<small>Step</small></div>
          </div>
          <p>{{ $staticPageData[0]['step1'] }}</p>
        </li>
        <li>
          <div class="divider1"><img src="{{ url('frontend/img/Group(31).png') }}"></div>
        </li>
        <li>
          <div class="round2">
            <div>2<small>Step</small></div>
          </div>
          <p>{{ $staticPageData[0]['step2'] }}</p>
        </li>
        <li>
          <div class="divider1"><img src="{{ url('frontend/img/Group(31).png') }}"></div>
        </li>
        <li>
          <div class="round3">
            <div>3<small>Step</small></div>
          </div>
          <p>{{ $staticPageData[0]['step3'] }}</p>
        </li>
      </ul>

      <div class="meet-doctor mt-5 mb-5">
        <div class="p-4 order-lg-1 order-md-1 order-2"><small>Meet Dr. Lawyer</small>
          <h2 class="h2-big">{{ $staticPageData[0]['section_heading5'] }}</h2>
          <p>{!! $staticPageData[0]['section_long_desc2'] !!}</p>

          <!-- <p>Tracye J. Lawyer MD, PhD, FAAOS, ABOS is boa   rd certified and fellowship trained in orthopedic sports medicine with a focus on cartilage joint regeneration. She specializes in arthroscopic and open surgery of the shoulder, elbow, and knee.</p>
          <ul>
            <li>Earned a PhD focusing on cartilage regeneration</li>
            <li>
              Completed a sports medicine fellowship at the University of Pittsburgh</li>
            <li>
              Earned her bachelor’s degree at Stanford University</li>
            <li>
              Was a two-sport collegiate athlete + awarded PAC-10 Player of the Year</li>
            <li>
              Competed in the U.S. Olympic Trials in track and field</li>
            <li>
              Received induction into the Stanford Hall of Fame</li>
          </ul> -->


        </div>
        <div class="meet-img order-lg-2 order-md-2 order-1">
          <img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image2'] }}"></div>
      </div>


      <div class="row" id="meet-cards">
        <div class="col-12 col-md-4 col-lg-4 d-flex align-items-stretch">
          <div class="card mb-4 mb-md-0">
            <div class="card-body"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image3'] }}">
              <div class="card-title">
                <h3><a href="#">{{ $staticPageData[0]['section_heading6'] }}</a></h3>
              </div>
              <div class="card-content">{{ $staticPageData[0]['section_short_desc6'] }}</div>
              <a href="{{ url('/login') }}" class="btn btn-card"><img src="{{ url('frontend/img/p-login.png') }}"> Patient Login</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4 col-lg-4 d-flex align-items-stretch">
          <div class="card mb-4 mb-md-0">
            <div class="card-body"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image4'] }}">
              <div class="card-title">
                <h3><a href="#">{{ $staticPageData[0]['section_heading7'] }}</a></h3>
              </div>
              <div class="card-content">{{ $staticPageData[0]['section_short_desc7'] }}</div>
              <a href="{{ url('/register') }}" class="btn btn-card"><img src="{{ url('frontend/img/Vector(25).png') }}"> Get Started</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4 col-lg-4 d-flex align-items-stretch">
          <div class="card mb-4 mb-md-0">
            <div class="card-body"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image5'] }}">
              <div class="card-title">
                <h3><a href="#">{{ $staticPageData[0]['section_heading8'] }}</a></h3>
              </div>
              <div class="card-content">{{ $staticPageData[0]['section_short_desc8'] }}</div>
              <a href="{{ url('/register') }}" class="btn btn-card"><img src="{{ url('frontend/img/Vector(25).png') }}"> Get Started</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <section class="news-tickler">
    <div class="marqueefy" data-mq-speed="50" tabindex="0">
      <div class="content">
        <span class="item">Discover our certified orthopedic surgeons, ready to offer you a valuable second opinion</span> * <span class="item">Discover our certified orthopedic surgeons, ready to offer you a valuable second opinion</span> * <span class="item">Discover our certified orthopedic surgeons, ready to offer you a valuable second opinion</span> * <span class="item">Discover our certified orthopedic surgeons, ready to offer you a valuable second opinion</span>
      </div>
    </div>
  </section>

  <section class="testimonials-reviews pt-5 pb-5">
    <div class="container">
      <div class="testi-row">
        <div class="testi-col">
          <div class="testi-img mb-20"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image6'] }}"></div>
          <div class="testi-slider-main mb-20 mb-md-0"><img src="{{ url('frontend/img/Group(35).png') }}">
            <!-- Swiper -->
            <div class="swiper mySwiper mt-4">
              <div class="swiper-wrapper">
                @foreach ($testimonials as $value )
                @if($value->status == 'active')
                <div class="swiper-slide text-center">
                  <h6>{{ $value->name }}</h6>
                  <p>“{{ $value->content }}”</p>
                </div>
                @endif
                @endforeach
               
                
              </div>
            </div>
            <div class="swiper-pagination"></div>
          </div>


        </div>
        <div class="testi-col">
          <div class="review-box mb-20"><img src="{{ url('frontend/img/Group 9568.png') }}"> 56 Reviews</div>
          <div class="testi-img mb-20"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image7'] }}"></div>
          <div class="hippa-box">
            <div class="d-flex justify-content-between gap-3 align-items-center">
              <img src="{{ url('frontend/img/Group 9590.png') }}">
              <div>
                <h6>{{ $staticPageData[0]['section_heading10'] }}</h6>
                <p class="mb-0"> {{ $staticPageData[0]['section_short_desc9'] }} </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="questions-ans pb-5">
    <div class="container">
      <h2 class="h2-big text-center mb-4">Questions?<br />We have answers</h2>

      <div class="accordion row" id="accordionExample">
        @foreach ($faqs as $faq )
          
       
        <div class="col-12 col-md-6">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
              {{ $faq->question }}
              </button>
            </h2>
            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong></strong> 
                {{ $faq-> description }}
              </div>
            </div>
          </div>
        </div>
        @endforeach
        
        <!-- <div class="col-12 col-md-6">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                How long will it take for me to receive my opinion / consultation?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the
                collapse plugin adds the appropriate classes that we use to style each element. These classes
                control the overall appearance, as well as the showing and hiding via CSS transitions. You can
                modify any of this with custom CSS or overriding our default variables. It's also worth noting that
                just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit
                overflow.
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Am I putting my relationship with my current doctor at risk?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the
                collapse plugin adds the appropriate classes that we use to style each element. These classes
                control the overall appearance, as well as the showing and hiding via CSS transitions. You can
                modify any of this with custom CSS or overriding our default variables. It's also worth noting that
                just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit
                overflow.
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Who will review my information?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the
                collapse plugin adds the appropriate classes that we use to style each element. These classes
                control the overall appearance, as well as the showing and hiding via CSS transitions. You can
                modify any of this with custom CSS or overriding our default variables. It's also worth noting that
                just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit
                overflow.
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                What joints are included? What about Spine injuries or problems?
              </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the
                collapse plugin adds the appropriate classes that we use to style each element. These classes
                control the overall appearance, as well as the showing and hiding via CSS transitions. You can
                modify any of this with custom CSS or overriding our default variables. It's also worth noting that
                just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit
                overflow.
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                How much does it cost?
              </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the
                collapse plugin adds the appropriate classes that we use to style each element. These classes
                control the overall appearance, as well as the showing and hiding via CSS transitions. You can
                modify any of this with custom CSS or overriding our default variables. It's also worth noting that
                just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit
                overflow.
              </div>
            </div>
          </div>
        </div> -->
      </div>






      <!--ul><li><a href="#q1" data-bs-toggle="collapse">When does an Orthopedic second opinion make sense?<span>+</span></a> <div id="q1" class="collapse pt-2 pb-2">
      adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div></li>
<li><a href="#q2" data-bs-toggle="collapse">How long will it take for me to receive my opinion / consultation?<span>+</span></a> <div id="q2" class="collapse pt-2 pb-2">
      adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div></li>
  <li><a href="#q3" data-bs-toggle="collapse">Am I putting my relationship with my current doctor at risk?<span>+</span></a> <div id="q3" class="collapse pt-2 pb-2">
      adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div></li>
<li><a href="#q4" data-bs-toggle="collapse">Who will review my information?<span>+</span></a> <div id="q4" class="collapse pt-2 pb-2">
      adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div></li>
  <li><a href="#q5" data-bs-toggle="collapse">What joints are included? What about Spine injuries or problems?<span>+</span></a> <div id="q5" class="collapse pt-2 pb-2">
      adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div></li>
<li><a href="#q6" data-bs-toggle="collapse">How much does it cost?<span>+</span></a> <div id="q6" class="collapse pt-2 pb-2">
      adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div></li>
  </ul-->

    </div>
  </section>

</main>

@endsection