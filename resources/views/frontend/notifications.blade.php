@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@extends('frontend.layouts.dashboardMain')
@section('dashboardMain.container')


<?php 
if($NotificationData->isNotEmpty())
{
foreach($NotificationData as $k => $notification)    
{
?>
      <div class="notifictions bg-white p-3 mb-3">
      <ul>
        <li class="d-flex justify-content-space-between gap-3">
          <div class="noti-text"><h5>{{ $notification->title }}</h5><p>{{ $notification->description  }}</p><span>{{ \Carbon\Carbon::parse($notification->created_at)->subMinutes(55)->diffForHumans() }}</span></div>
          <a href="{{ URL('/delete-notification') }}/{{ Crypt::encrypt($notification->id) }}" onclick="return confirm('Do you want to delete this notification?')" class="delete">
              <img src="{{ url('/public/frontend/img/Vector(39).png') }}"></a>
        </li>
      </ul>
      </div>
<?php } ?>

<?php } else { ?>
    <div class="notifictions bg-white p-3 mb-3">
      <ul>
        <li class="d-flex justify-content-space-between gap-3">
          <div class="noti-text"><span>Notification not found.</span></div>
        </li>
      </ul>
    </div>
<?php } ?>

@if($pagination == 1)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            {{-- Previous Page Link --}}
            @if ($NotificationData->onFirstPage())
                <li class="page-item disabled prev">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @else
                <li class="page-item prev">
                    <a class="page-link" href="{{ $NotificationData->previousPageUrl() }}">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($NotificationData->links()->elements[0] as $page => $url)
                @if ($page == $NotificationData->currentPage())
                    <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($NotificationData->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $NotificationData->nextPageUrl() }}">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @else
                <li class="page-item disabled next">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif

</main>
</div>

<!-- Jquery -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script> -->
<!-- Bootstrap 5 JS Bundle -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script> -->
</body>
</html>
@endsection
