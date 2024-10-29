@extends('frontend.layouts.main')
@section('main.container')
<main id="page-main">
  <section class="top-banner d-flex justify-content-center align-items-center">
    <div class="container">
      <h1 class="h1-big text-center">{{ $jointData[0]->page_name }}</h1>
      <nav aria-label="breadcrumb">
  <ol class="breadcrumb justify-content-center mb-0">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $jointData[0]->page_name }}</li>
  </ol>
</nav>
    </div>
  </section>
  <section class="about-one pt-5 pb-5" id="pro-page">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-12 col-md-6 col-lg-6 order-2 order-md-1">
        <div class="text-contents d-flex gap-3 flex-column"><p>
          {!! $jointData[0]->content1 !!}
        </p>
<a href="{{ url('/register') }}" class="btn btn-card mt-0"><img src="{{ url('frontend/img/p-login.png') }}"> Create An Account With Our Patient Portal</a></div>
      </div>
      <div class="col-12 col-md-6 col-lg-6 order-1 order-md-2">
        <div class="main-imgs mb-4 mb-md-0">
          <img src="{{ url('public/homepage_img') }}/{{ $jointData[0]->name }}/{{ $jointData[0]->photo1 }}">
        </div>
      </div>
    </div>

 <div class="main-imgs img-height mt-5 mb-5">
          <img src="{{ url('public/homepage_img') }}/{{ $jointData[0]->name }}/{{ $jointData[0]->photo2 }}">
          
        </div>

<div class="row align-items-center">
      <div class="col-12 col-md-6 col-lg-6 order-2 order-md-1">
        <div class="text-contents d-flex gap-3 flex-column"><h2 class="h2-big">{{ $jointData[0]->heading1 }}</h2><p>{!! $jointData[0]->content2 !!}</p></div>
      </div>
      <div class="col-12 col-md-6 col-lg-6 order-1 order-md-2">
        <div class="main-imgs mb-4 mb-md-0">
          <img src="{{ url('public/homepage_img') }}/{{ $jointData[0]->name }}/{{ $jointData[0]->photo3 }}">
        </div>
      </div>
    </div>


<div class="row align-items-center mt-5 mb-5">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="main-imgs mb-4 mb-md-0">
          <img src="{{ url('public/homepage_img') }}/{{ $jointData[0]->name }}/{{ $jointData[0]->photo4 }}">
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-6">
        <div class="text-contents d-flex gap-3 flex-column"><h2 class="h2-big">{{ $jointData[0]->heading2 }}</h2><p>{!! $jointData[0]->content3 !!}</p></div>
      </div>
     </div>


<div class="row align-items-center">
      <div class="col-12 col-md-6 col-lg-6 order-2 order-md-1">
        <div class="text-contents d-flex gap-3 flex-column"><h2 class="h2-big">{{ $jointData[0]->heading3 }}</h2><p>{!! $jointData[0]->content4 !!}</p></div>
      </div>
      <div class="col-12 col-md-6 col-lg-6 order-1 order-md-2">
        <div class="main-imgs mb-4 mb-md-0">
          <img src="{{ url('public/homepage_img') }}/{{ $jointData[0]->name }}/{{ $jointData[0]->photo5 }}">
        </div>
      </div>
    </div>


<div class="consults mt-5 pt-5 pb-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="text-contents d-flex gap-3 flex-column justify-content-center text-center">
  <h2 class="h2-big">{{ $jointData[0]->heading4 }}</h2>
  <p>{!! $jointData[0]->content5  !!}</p>
<div class="d-flex justify-content-center"><a href="{{ url('/register ') }}" class="btn btn-card mt-0"><img src="{{ url('frontend/img/p-login.png') }}"> Sign Up To Get Your Second Opinion</a></div>
</div>
    </div>
  </div>
</div>

  </div>
</section>
</main>
@endsection