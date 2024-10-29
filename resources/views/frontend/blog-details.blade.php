@extends('frontend.layouts.main')
@section('main.container')
<main id="page-main">
  <section class="top-banner d-flex justify-content-center align-items-center">
    <div class="container">
      <h1 class="h1-big text-center">Blogs</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center mb-0">
          <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Blogs</li>
        </ol>
      </nav>
    </div>
  </section>
  <section class=" pt-5 pb-5">
    <div class="container">
      <div class="row">
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
        <div class="col-12 col-md-7 col-lg-8">
          <div class="blog-articles d-flex gap-3 flex-column mb-3 pb-3 border-bottom-1">
            <div class="blog-card-img"><a href="#"><img src="{{ $articleData->image ? url('public/article_images/' . $articleData->image ) : url('frontend/img/Rectangle 78.png') }}"></a></div>
            <div class="blog-card-title">
              <h2 class="h2-big">{{ $articleData->title }}</h2>
              <div class="posted-by">By Admin - {{ date('M j Y ', strtotime($articleData->created_at)) }} -
                {{ $articleData->category->category_name ?? ''}}
              </div>
            </div>
            <div class="blog-discription d-flex gap-3 flex-column">
              {!! $articleData->short_desc !!}
              {!! $articleData->long_desc !!}

            </div>

          </div>
          <div class="share-with d-flex gap-2 align-items-center pb-3 border-bottom-1 mb-4"><span>Share with:</span>
            <ul class="social d-flex gap-2 mb-0">
              <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog-details', $articleData->id)) }}" target="_blank"><img src="{{ url('frontend/img/Group 9640.png') }}"></a></li>
                <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog-details', $articleData->id))  }}&text={{ $articleData->title }}" target="_blank"><img src="{{ url('frontend/img/Group 9639.png') }}"></a></li>
                <li>
                  <a href="javascript:void(0)" onclick="copyToClipboard('{{ route('blog-details', $articleData->id) }}')">
                    <img src="{{ url('frontend/img/Group 9638.png') }}" alt="Copy Link">
                  </a>
                </li>
                <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to=&su={{ urlencode($articleData->title) }}&body=Check out this article: {{ urlencode(route('blog-details', $articleData->id))  }}" target="_blank"><img src="{{ url('frontend/img/Group 9672.png') }}"></a></li>

                <div></div>
            </ul>
          </div>

         
          @if($user)
          <div class="leave-comments mb-4 border-bottom-1">
         
            <h4 class="mb-4">Leave a Comment</h4>

            <div class="d-flex gap-3">
              <div class="user-img">
              @php
                      $profilePhotoPath = null;                      
                      if ($user->user_type == "doctor") {
                        $profilePhotoPath = url('public/doctor_photos/' . $user->profile_photo);
                      } elseif ($user->user_type == "patient") {
                        $profilePhotoPath = url('public/patient_photos/' . $user->profile_photo);
                      }
                  @endphp

                  @if (!empty($user->profile_photo))
                      <img src="{{ $profilePhotoPath }}">
                  @else
                      <img src="{{ url('frontend/img/Group 9657.png') }}">
                  @endif
              </div>
              <div class="w-100 d-flex flex-column">
              <form action="{{ route('comments.store') }}" method="POST">                
              @csrf
              <input type="hidden" name="article_id" value="{{ $articleData->id }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="row">
                  <div class="col-12 col-md-12">
                    <div class="mb-4"><textarea class="form-control" name="comment" placeholder="Comment" required></textarea></div>
                  </div>
                  <div class="col-12 col-md-12">
                    <div class="mb-4"><button type="submit" class="btn btn-card">Post Comments</button></div>
                  </div>
                </div>
              </form>
              </div>
            </div>
          </div>
          @endif
          
    

          @if ( !empty ($articleData->comments))
          <div class="blogs-comments">
            @if($articleData->comments->count() > 0)
            <h4 class="mb-4">{{ $articleData->comments->count() }} Comments</h4>
            @endif
            @foreach ($articleData->comments as $comment )
            <div class="main-comments border-bottom-1 mb-3">            
              <div class="posted-comments d-flex gap-3 mb-3 align-items-center">
              <div class="user-img">
                  @php
                      $profilePhotoPath = null;                      
                      if ($comment->user->user_type == "doctor") {
                        $profilePhotoPath = url('public/doctor_photos/' . $comment->user->profile_photo);
                      } elseif ($comment->user->user_type == "patient") {
                        $profilePhotoPath = url('public/patient_photos/' . $comment->user->profile_photo);
                      }
                  @endphp

                  @if (!empty($comment->user->profile_photo))
                      <img src="{{ $profilePhotoPath }}">
                  @else
                      <img src="{{ url('frontend/img/Group 9657.png') }}">
                  @endif
              </div>

                <div class="d-flex flex-column gap-1">{{ ucfirst($comment->user->first_name. " ". $comment->user->last_name ) }} 
                  <span>{{ date('M j Y H:i', strtotime($comment->created_at)) }}</span></div>
              </div>
              <p>{{ ucfirst($comment->comment)}}</p>
               
            </div>
            @endforeach
          </div>
          @endif

        </div>
        <div class="col-12 col-md-5 col-lg-4">
          <div class="recent-blogs mb-4">
            <h3 class="ps-3 pe-3">Recent Blogs</h3>
            <div class="recent-blogs-body p-3">
            <ul>
                @if($articleRecentData && $articleRecentData->isNotEmpty())
                @foreach($articleRecentData as $recentArticle)
                <li>
                  <div class="rs-img">
                    <a href="{{ url('/blog-details') }}/{{$recentArticle->id  }}">
                      <img src="{{ $recentArticle->image ? url('public/article_images/' . $recentArticle->image) : url('frontend/img/Rectangle 78.png') }}" alt="Article Image">
                    </a>
                  </div>
                  <a href="{{ url('/blog-details') }}/{{$recentArticle->id  }}">{{ $recentArticle->title }}</a>
                </li>
                @endforeach
                @else
                <li>No record found</li>
                @endif
              </ul>
            </div>
          </div>

          <div class="recent-blogs">
            <h3 class="ps-3 pe-3">Categories</h3>
            <div class="recent-Categories">
            <ul>

                @if($categoryData && $categoryData->isNotEmpty())
                  @foreach($categoryData as $cat)
                  <li><a href="{{ url('/blog') }}/{{ $cat->id  }}">{{ $cat->category_name }}</a><span>({{ $cat->articles_count }})</span></li>
                  @endforeach
                  @else
                  <li>No record found</li>
                  @endif
              

            </ul>
            </div>
          </div>

        </div>
      </div>



    </div>
  </section>
  <script>
    function copyToClipboard(url) {
      // Create a temporary input element
      var tempInput = document.createElement("input");
      tempInput.value = url;
      document.body.appendChild(tempInput);

      // Select the text and copy it to the clipboard
      tempInput.select();
      tempInput.setSelectionRange(0, 99999); // For mobile devices
      document.execCommand("copy");

      // Remove the temporary input element
      document.body.removeChild(tempInput);

      // Optional: Notify the user
      alert("Link copied to clipboard!");
    }
  </script>
</main>
@endsection