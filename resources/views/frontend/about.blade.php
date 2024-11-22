@extends('frontend.layouts.main')
@section('main.container')

<main id="page-main">
  <section class="top-banner d-flex justify-content-center align-items-center">
    <div class="container">
      <h1 class="h1-big text-center">{{ $staticPageData[0]['section_name'] }}</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center mb-0">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $staticPageData[0]['section_name'] }}</li>
        </ol>
      </nav>
    </div>
  </section>

  <section class="about-one pt-5 pb-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-md-6 col-lg-6 order-2 order-md-1">
          <div class="text-contents d-flex gap-3 flex-column">
            <span>{{ $staticPageData[0]['section_heading1'] }}</span>
           
              {!! $staticPageData[0]['section_short_desc1'] !!}
              {!! $staticPageData[0]['section_long_desc1'] !!}
 

           
            <a href="{{ url('/login') }}" class="btn btn-card mt-3"><img src="{{ url('frontend/img/p-login.png') }}"> Patient Login</a>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6 order-1 order-md-2">
          <div class="video-img mb-4 mb-md-0">
            <div class="img-one">
              <!-- <a href="#" class="play-btn"><span><i class="fas fa-play"></i></span> -->
            </a>
             <img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image1'] }}"></div>
            <div class="bottom-text1">
              <ul>
                <li><img src="{{ url('frontend/img/Group 9669.png') }}">Brand Certified</li>
                <li><img src="{{ url('frontend/img/Group 9670.png') }}">Fellowship Trained</li>
                <li><img src="{{ url('frontend/img/Group 9671.png') }}">Stanford Hall of Fame</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="process pt-0 pb-5">
    <div class="container">
      <h2 class="text-center h2-big mb-4">Our Process</h2>
      <ul class="process-steps">
        <li>
          <div class="round1">
            <div>1<small>Step</small></div>
          </div>
          <p>Fill the form and attach the files related to your medical record</p>
        </li>
        <li>
          <div class="divider1"><img src="{{ url('frontend/img/Group(31).png') }}"></div>
        </li>
        <li>
          <div class="round2">
            <div>2<small>Step</small></div>
          </div>
          <p>Select the E-visit type from Written report, Phone Call, or Video Consultation</p>
        </li>
        <li>
          <div class="divider1"><img src="{{ url('frontend/img/Group(31).png') }}"></div>
        </li>
        <li>
          <div class="round3">
            <div>3<small>Step</small></div>
          </div>
          <p>Easily make payments within minutes securely</p>
        </li>
      </ul>
    </div>
  </section>


  <section class="about-one pt-0 pb-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-md-6 col-lg-6">
          <div class="video-img video-img1 mb-4 mb-md-0">
            <div class="img-one"><img src="{{ url('public/') }}/{{ $staticPageData[0]['section_image2'] }}"></div>
            <div class="bottom-text1 flex-column">
             {!! $staticPageData[0]['section_short_desc3'] !!}
              <!-- <span class="d-flex justify-content-end w-100">-Thomas T.</span> -->
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
          <div class="text-contents d-flex gap-3 flex-column">
            {!! $staticPageData[0]['section_short_desc2'] !!}
            <h2 class="h2-big">{!! $staticPageData[0]['section_heading2'] !!}</h2>
            {!! $staticPageData[0]['section_long_desc2'] !!}</p>

             <a href="{{ url('/login') }}" class="btn btn-card mt-3"><img src="{{ url('frontend/img/p-login.png') }}"> Patient Login</a>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

@endsection