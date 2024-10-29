@extends('frontend.layouts.main')
@section('main.container')
    <main id="page-main">
        <section class="top-banner d-flex justify-content-center align-items-center">
            <div class="container">
                <h1 class="h1-big text-center">Support Center</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Support</li>
                    </ol>
                </nav>
            </div>
        </section>

        <section class="about-one pt-5 pb-5">
            <div class="container">
                <div class="row align-items-center">
                        <div class="col-12 col-md-6 col-lg-6">
                        <section class="contact-section">
                            <h2>Support Information</h2>
                            <p>If you need further assistance, you can reach us at:</p>
                            <p><strong>Email:</strong> lawyert@slhs.org</p>
                        </section>
                        </div>
                    <div class="col-12 col-md-6 col-lg-6 ps-0 ps-md-4">
                        <div class="video-img ps-3 ps-md-0 mb-4 mb-md-0">
                            <div class="img-one"><img src="{{ url('public/img/ladydoctor.jpg') }}" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection