@extends('frontend.layouts.main')
@section('main.container')
    <main id="page-main">
        <section class="top-banner d-flex justify-content-center align-items-center">
            <div class="container">
                <h1 class="h1-big text-center">Contact Us</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                    </ol>
                </nav>
            </div>
        </section>

        <section class="about-one pt-5 pb-5">
            <div class="container">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="row align-items-center">
                        <div class="col-12 col-md-6 col-lg-6">
                            <h4 class="mb-4">Send Us Message</h4>
                            <form action="{{ URL('/add_contact_us') }}" method="POST">
                            @csrf
                                <div class="mb-4"><label>Full Name</label><input type="text" name="full_name" class="form-control" placeholder="Full Name" required/></div>
                                <div class="mb-4"><label>Email Address</label><input type="email" name="email" class="form-control" placeholder="Email Address" required/></div>
                                <div class="mb-4"><label>Phone No.</label><input type="tel" name="phone" class="form-control format_phone" placeholder="Phone No." required/></div>
                                <div class="mb-4"><label>Message</label><textarea name="message" class="form-control" placeholder="Message" required></textarea></div>
                                <div class="mb-4"><button type="submit" class="btn btn-card mt-3">Submit Request</button></div>
                            </form>
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
    <script>
        $(document).ready(function() 
        {

        if(document.getElementsByClassName('format_phone').length > 0) {
            document.querySelector('.format_phone').addEventListener('input', function (e) {
                var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });
        }
        
        $.validator.addMethod("lettersOnly", function(value, element) {
            return this.optional(element) || /^[A-Za-z\s'-]+$/.test(value);
        }, "Please use letters only (no numbers or special characters).");
    })
    </script>
@endsection