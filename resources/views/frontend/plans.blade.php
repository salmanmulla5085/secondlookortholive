@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')



<?php if(!empty($check_app) && $check_app > 0) {
    if(!empty($selected_plan)){
        $plan = $selected_plan;
    } else {
        $plan = 'None';
    }
} else {
    $plan = 'None';
} ?>

<div class="box-main p-3 bg-white margin-15-b radius8 text-dark">
  <span class="text-muted">Active Plan :</span> {{ $plan }}
</div>

<?php
    $sql = "SELECT * FROM tbl_plans";
    $tbl_plans = DB::select($sql);
    $tbl_plans = collect($tbl_plans);					                
    $tbl_plans =  $tbl_plans->toArray();

    ?>

<div class="row justify-content-center mt-5">
  <div class="col-12 col-lg-10 col-md-12">
    <div class="row justify-content-center">
      @if($tbl_plans[0]->status && $tbl_plans[0]->status == 'Active')
        <div class="col-12 col-md-4 d-flex align-items-stretch">
          <div class="plan-box purple-bg1 bg-white margin-15-b radius8 text-dark w-100">
            <div class="plan-header"><img src="{{ url('/public/frontend/img/Layer 4(1).png') }}"><h3>
            {{ $tbl_plans[0]->plan_type }}  
            </h3></div>
            <div class="plan-body"><h2>{{ $tbl_plans[0]->plan_amount }}  </h2>
              <ul><li>{{ $tbl_plans[0]->plan_detail }}</li></ul>
            </div>
            <div class="plan-footer p-3 text-center"><a href="{{ url('/book_appointment') }}/{{ $tbl_plans[0]->id }}/{{ 0 }}/{{ 'report_review' }}">Subscribe Now</a></div>
          </div>
        </div>
      @endif
      @if($tbl_plans[1]->status && $tbl_plans[1]->status == 'Active')
        <div class="col-12 col-md-4 d-flex align-items-stretch">
          <div class="plan-box orange-bg1 bg-white margin-15-b radius8 text-dark w-100">
            <div class="plan-header"><img src="{{ url('/public/frontend/img/Vector(33).png') }}"><h3>
            {{ $tbl_plans[1]->plan_type }}  </h3></div>
            <div class="plan-body"><h2>{{ $tbl_plans[1]->plan_amount }}</h2>
              <ul><li>{{ $tbl_plans[1]->plan_detail }}</li></ul>
            </div>
            <div class="plan-footer p-3 text-center"><a href="{{ url('/book_appointment') }}/{{ $tbl_plans[1]->id }}/{{ 0 }}/{{ 'phone_consultation' }}">Subscribe Now</a></div>
          </div>
        </div>
      @endif
      @if($tbl_plans[2]->status && $tbl_plans[2]->status == 'Active')
        <div class="col-12 col-md-4 d-flex align-items-stretch">
          <div class="plan-box lgreen-bg1 bg-white margin-15-b radius8 text-dark w-100">
            <div class="plan-header"><img src="{{ url('/public/frontend/img/Group 9617(1).png') }}"><h3>
            {{ $tbl_plans[2]->plan_type }}  </h3></div>
            <div class="plan-body"><h2>{{ $tbl_plans[2]->plan_amount }}</h2>
              <ul><li>{{ $tbl_plans[2]->plan_detail }}</li></ul>
            </div>
            <div class="plan-footer p-3 text-center"><a href="{{ url('/book_appointment') }}/{{ $tbl_plans[2]->id }}/{{ 0 }}/{{ 'video_consultation' }}">Subscribe Now</a></div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
  var ExtPlan = '<?php echo $selected_plan;?>';
  var plan = '<?php echo $plan;?>';
  
  if(ExtPlan && ExtPlan != '' && ExtPlan != 'undefined' && ExtPlan == 'Video Consultation' && plan == 'Video Consultation'){
      $('.lgreen-bg1').css({
        'transform': 'scale(1.1)',
        'z-index': '2',
        'box-shadow': '0px 7px 15px rgba(0, 0, 0, 0.1)',
        'cursor': 'pointer'
      });
    } 
    
    if(ExtPlan && ExtPlan != '' && ExtPlan != 'undefined' && ExtPlan == 'Phone Consultation' && plan == 'Phone Consultation'){
      $('.orange-bg1').css({
        'transform': 'scale(1.1)',
        'z-index': '2',
        'box-shadow': '0px 7px 15px rgba(0, 0, 0, 0.1)',
        'cursor': 'pointer'
      });
    } 

    if(ExtPlan && ExtPlan != '' && ExtPlan != 'undefined' && ExtPlan == 'Report Review' && plan == 'Report Review'){
      $('.purple-bg1').css({
        'transform': 'scale(1.1)',
        'z-index': '2',
        'box-shadow': '0px 7px 15px rgba(0, 0, 0, 0.1)',
        'cursor': 'pointer'
      });
    }
</script>


</main>
</div>

<!-- Jquery -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>-->
<!-- Bootstrap 5 JS Bundle -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>-->
</body>
</html>
@endsection