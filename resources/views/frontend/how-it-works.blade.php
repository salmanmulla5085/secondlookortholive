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
  <section class="find-doctors pt-5 pb-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 col-md-6 col-lg-6">
          {!! $staticPageData[0]['section_heading1'] !!}
         {!! $staticPageData[0]['section_long_desc1'] !!}
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
    </div>
  </section>

</main>
@endsection