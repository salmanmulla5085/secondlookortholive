@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

<div class="">

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

      @if (session('error'))
      <div class="alert alert-danger">
      {{ session('error') }}
      </div>
      @endif

      @if (session('warning'))
      <div class="alert alert-warning">
      {{ session('warning') }}
      </div>
      @endif
</div>

<div class="row">
  <div class="col-12 col-md-7 col-lg-8 col-xl-8 order-2 order-md-1">
    <form id="payment-form" action="{{ url('/book_appointment_step3') }}" method="POST">
      @csrf <!-- CSRF Token for Laravel -->
      <div class="payments-bg bg-white p-3 mb-3">
        <div class="d-flex flex-column gap-3 mb-3">
          <h5 class="mb-0 text-dark">Payment Amount</h5>
          <h2 class="mb-0 text-dark">
            <?php if($appointment && !empty($appointment['amount'])){ echo '$'.$appointment['amount'];}?>
          </h2>
        </div>

        <div class="stripe-pay d-flex justify-content-between align-items-center p-3 bg-white gap-3 mb-3 flex-column">
          <a href="#" class="d-flex justify-content-between align-items-center w-100 mb-3 ">
            <img src="{{ url('/public/frontend/img/image 19.png') }}">
          </a> 
          
          <div class="row w-100">
            <div class="col-12 col-md-12 col-lg-9">
              <div class="mb-3 position-relative pay-icon">
                <label for="card-element">Card Number</label>
                <!-- Stripe Element will be inserted here -->
                <div id="card-element" class="form-control"></div>
                <div id="card-errors" class="text-danger mt-2" role="alert"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-3">
          <button type="submit" class="btn border-radius-0 btn-orange ps-5 pe-5" id="payButton">Pay</button>
        </div>
      </div>
    </form>
  </div>

  <div class="col-12 col-md-5 col-lg-4 col-xl-4 order-1 order-md-2">
    <div class="payments-bg summary bg-white p-3 mb-3">
      <h5 class="text-dark mb-0">Appointment Summary</h5>
      <hr />
      <ul>
        @if($appointment['appointmentType'] != "Report Review")
        <li><span>Date:</span> <?php if($app_start_date && !empty($app_start_date)){ echo $app_start_date;}?></li>
        <li><span>Time:</span> <?php if($app_start_time && !empty($app_start_time)){ echo $app_start_time;}?></li>
        @endif

        <li><span>Doctor:</span> Dr. <?php if($doctor_name && !empty($doctor_name)){ echo $doctor_name->first_name.' '.$doctor_name->last_name;}?></li>
        <li><span>Joints:</span> <?php if($interests && !empty($interests)){ echo $interests;}?></li>
        <li><span>Appointment Type:</span> <?php if($appointment && !empty($appointment)){ echo $appointment['appointmentType'];}?></li>
        <li><span>Total Pay:</span> <?php if($appointment && !empty($appointment['amount'])){ echo '$'.$appointment['amount'];}?></li>
      </ul>
    </div>
  </div>
</div>

<script>
// Set your Stripe public key
const stripe = Stripe('pk_test_51H1mhNBJYyRfYsmarPfQ3mKYVqPrN77g8CuStOvll6SvJ22zOFZE9ojoPZlR9TNgd88lfwIFltkyrKbDWTfJptvi00vVIrXFaX'); // Replace with your Stripe public key
const elements = stripe.elements();

// Create an instance of the card Element
const card = elements.create('card');

// Add an instance of the card Element into the `card-element` div
card.mount('#card-element');

// Handle form submission
const form = document.getElementById('payment-form');

form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            // Send the token to your server
            stripeTokenHandler(result.token);
        }
    });
});

// Submit the token and the form to your server
function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    $('#payButton').prop('disabled', true);
    // Submit the form
    form.submit();
}

// $(document).ready(function() 
// {
//     // $('#payButton').click(function(){
//     //   if ($("#card-errors").text().trim() === "") {
//     //     $(this).prop('disabled', true);
//     //   }
//     // });
//     // Push a state to the history to disable back navigation
//     history.pushState(null, null, window.location.href);

//     // Prevent back button by pushing state when popstate event is triggered
//     window.onpopstate = function(event) {
//         history.pushState(null, null, window.location.href);
//     };

//     // Additionally, prevent default action for popstate events
//     window.addEventListener('popstate', function(event) {
//         history.pushState(null, null, window.location.href);
//     }, false);


//     window.addEventListener('popstate', function(event) {
//         event.preventDefault(); // Prevent the default action
//         history.pushState(null, null, window.location.href);
//     });


//     // Set initial state and hash
//     history.pushState(null, null, window.location.href);
//     window.location.hash = "no-back";

//     // Prevent back navigation by manipulating the state and hash
//     window.onpopstate = function() {
//         if (window.location.hash === "#no-back") {
//             history.pushState(null, null, window.location.href);
//         }
//     };

//     // Additionally handle hash change event
//     $(window).on('hashchange', function() {
//         if (window.location.hash !== "#no-back") {
//             window.location.hash = "no-back";
//         }
//     });

//     history.pushState(null, null, window.location.href);
//     window.onpopstate = function(event) {
//         window.location.href = "{{ URL('/') }}/patient-dashboard"; // Change to your desired redirect URL
//     };

// });

</script>
@endsection
