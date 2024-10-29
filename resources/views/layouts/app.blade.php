<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <!-- <link rel="icon" type="image/png" href="/img/favicon.png"> -->
    <title>
        Second Look Ortho
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ url('/') }}/public/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ url('/') }}/public/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <!-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="{{ url('/') }}/public/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ url('/') }}/public/assets/css/argon-dashboard.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

     <!-- CKEditor CDN -->
     <script src="https://cdn.tiny.cloud/1/f5drhc27xryzqxlqc0t0lgy8nspew2kh6wmftymvvzrkr6nf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
     <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- jQuery Library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI JS -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     @yield('head')

</head>

<body class="{{ $class ?? '' }}">

    
    
        @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'login', 'register', 'recover-password', 'rtl', 'virtual-reality']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile-static']))
                <div class="min-height-300 bg-primary position-fixed w-100"></div>
            @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                <!--<div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('<?=url("/public/img/ladydoctor.jpg") ?>'); background-position-y: 50%;">-->
                <!--    <span class="mask bg-primary opacity-6"></span>-->
                <!--</div>-->
                <div class="position-absolute w-100 min-height-300 top-0">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
            @include('layouts.navbars.sidenav')
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @include('components.fixed-plugin')
        @endif
    
    <!--   Core JS Files   -->
    <script src="{{ url('/') }}/public/assets/js/core/popper.min.js"></script>
    <script src="{{ url('/') }}/public/assets/js/core/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/public/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ url('/') }}/public/assets/js/plugins/smooth-scrollbar.min.js"></script>
    
    <script>
    if (typeof jQuery == 'undefined') {
        console.error('jQuery is not loaded');
    } else {
        console.log('jQuery version:', jQuery.fn.jquery);
        // Your jQuery-dependent code here
    }
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ url('/') }}/public/assets/js/argon-dashboard.js"></script>
    


</body>
<script>
   
    let inactivityTime = function () {
        let time;
        const logoutAfter = 15  * 60 * 1000; // 15 minutes in milliseconds

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onscroll = resetTimer;
        document.onclick = resetTimer;

        function logout() {
            window.location.href = '{{ URL("/admin_logout") }}'; // Replace with your logout route
        }

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(logout, logoutAfter);
        }
    };

    inactivityTime();
</script>


@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    setInterval(function() {
    $.get('/refresh-csrf').done(function(data) {
        $('meta[name="csrf-token"]').attr('content', data); // Update the meta tag
    });
    }, 10 * 60 * 1000); // Refresh every 10 minutes

</script>
@endsection

</html>
