@extends('frontend.layouts.main')
@section('main.container')
<main id="page-main">
  <section class="top-banner d-flex justify-content-center align-items-center">
    <div class="container">
      <h1 class="h1-big text-center">FAQs</h1>
      <nav aria-label="breadcrumb">
  <ol class="breadcrumb justify-content-center mb-0">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">FAQs</li>
  </ol>
</nav>
    </div>
  </section>
  <section class="questions-ans pt-5 pb-5">
  <div class="container"><h2 class="h2-big text-center mb-4"><span class="color-orange">Questions?</span> We have answers</h2>

<div class="accordion row" id="accordionExample">

@foreach ($faqs as $faq)
    <div class="col-12 col-md-12">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                    {{ $faq->question }}
                </button>
            </h2>
            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                {!! $faq->description !!}
                </div>
            </div>
        </div>
    </div>
@endforeach

</div>





  </div>
</section>

</main>
@endsection