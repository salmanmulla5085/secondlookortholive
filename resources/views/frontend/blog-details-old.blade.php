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
        <div class="col-12 col-md-7 col-lg-8">
          
          <div class="blog-articles d-flex gap-3 flex-column mb-3 pb-3 border-bottom-1">
            <div class="blog-card-img"><a href="#"> <img
                  src="{{ url('public/article_images') }}/{{ $articleData->image }}">
              </a></div>
            <div class="blog-card-title">
              <h2 class="h2-big"><a href="#">{{ $articleData->title }}</a></h2>
              <div class="posted-by">By Admin - {{ date('j F Y H:i', strtotime($articleData->created_at)) }} -
                {{ $articleData->category->category_name }}, Ortho
              </div>
            </div>
            <div class="blog-discription d-flex gap-3 flex-column">
              <p>{{ $articleData->short_desc }}</p>
            </div>
            <div class="blog-discription d-flex gap-3 flex-column">
              <p>{{ $articleData->long_desc }}</p>
            </div>
            <div class="blog-buttons d-flex align-items-center justify-content-between">
              <a href="#" class="btn btn-card">Continue Reading</a>
              <ul class="social d-flex gap-2">
                <li><a href="#"><img src="{{ url('frontend/img/Group 9640.png') }}"></a></li>
                <li><a href="#"><img src="{{ url('frontend/img/Group 9639.png') }}"></a></li>
                <li><a href="#"><img src="{{ url('frontend/img/Group 9638.png') }}"></a></li>
                <li><a href="#"><img src="{{ url('frontend/img/Group 9672.png') }}"></a></li>
              </ul>
            </div>
          </div>
          


          <!-- <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled prev">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><img
                    src="{{ url('frontend/img/Group 9650.png') }}"></a>
              </li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#"><img src="{{ url('frontend/img/Group 9650.png') }}"></a>
              </li>
            </ul>
          </nav> -->

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
                    <a href="#">
                      <img src="{{ $recentArticle->image ? url('public/article_images/' . $recentArticle->image) : url('frontend/img/Rectangle 82.png') }}" alt="Article Image">
                    </a>
                  </div>
                  <a href="#">{{ $recentArticle->title }}</a>
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
                <li><a href="#">{{ $cat->category_name }}</a><span>({{ $cat->articles_count }})</span></li>
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

</main>
@endsection