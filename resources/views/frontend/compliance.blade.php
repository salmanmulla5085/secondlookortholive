@extends('frontend.layouts.main')
@section('main.container')

<main id="page-main">
    <section class="top-banner d-flex justify-content-center align-items-center">
        <div class="container">
            <h1 class="h1-big text-center">{{ $compliance->section_name}}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $compliance->section_name}}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="about-one pt-5 pb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 col-lg-12 order-2 order-md-1">
                    <div class="text-contents ">
                        <h2 class="h2-big">{{ $compliance->section_name}}</h2>
                        {!! $compliance->section_short_desc1 !!}

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection