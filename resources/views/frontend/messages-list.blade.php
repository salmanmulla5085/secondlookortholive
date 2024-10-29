@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')

@if(isset($appointments) && $appointments->count() > 0)
        @foreach($appointments as $appointment)
            <div class="message1 bg-white p-3 d-flex justify-content-space-between gap-3 mb-3">
                <ul class="list-one w-100">
                    <li><span>Date</span>{{ $appointment->end->format('d M Y') }}</li>
                    <li><span>Time</span>{{ $appointment->end->format('g:i A') }}</li>
                    <li><span>Symptoms</span>{{ $appointment->symptoms ?? 'N/A' }}</li>
                    <li><span>Category</span>{{ $appointment->category ?? 'N/A' }}</li>
                    <li><span>Doctor</span>{{ $appointment->name ?? 'N/A' }}</li>
                </ul>
                <a href="{{ url('/messages') }}">
                    <img src="{{ url('/public/frontend/img/Vector(37).png') }}" alt="Icon">
                </a>
            </div>
        @endforeach

@endif
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-end">
    <li class="page-item disabled prev">
      <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><img src="{{ url('/public/frontend/img/Group 9650.png') }}"></a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#"><img src="{{ url('/public/frontend/img/Group 9650.png') }}"></a>
    </li>
  </ul>
</nav>

</main>
</div>

<!-- Jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>
@endsection