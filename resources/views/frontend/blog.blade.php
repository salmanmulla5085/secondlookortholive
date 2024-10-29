@extends('frontend.layouts.main')
@section('main.container')
<style>
  .page-item.active .page-link {
 
  background-color: #fd890d !important;

}
</style>
<main id="page-main">
  <section class="top-banner d-flex justify-content-center align-items-center">
    <div class="container">
      <h1 class="h1-big text-center">Blogs</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center mb-0">
          <li class="breadcrumb-item"><a href="{{ url('')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Blogs</li>
        </ol>
      </nav>
    </div>
  </section>
  <section class=" pt-5 pb-5">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-7 col-lg-8">
        @if($articleData->isNotEmpty())
          @foreach($articleData as $article)
          <div class="blog-articles d-flex gap-3 flex-column mb-3 pb-3 border-bottom-1">
            <div class="blog-card-img"><a href="{{ url('/blog-details') }}/{{ $article->id  }}"> <img
                  src="{{ $article->image ? url('public/article_images/' . $article->image) : url('frontend/img/Rectangle 78.png') }}">
              </a></div>
            <div class="blog-card-title">
              <h2 class="h2-big"><a href="{{ url('/blog-details') }}/{{ $article->id  }}">{{ $article->title }}</a></h2>
              <div class="posted-by">By Admin - {{ date('M j Y', strtotime($article->created_at)) }} -
                {{ $article->category->category_name ?? ''}}
              </div>
            </div>
            <div class="blog-discription d-flex gap-3 flex-column">
              <p>{!! $article->short_desc !!}</p>
            </div>
            <div class="blog-buttons d-flex align-items-center justify-content-between">
              <a href="{{ url('/blog-details') }}/{{ $article->id  }}" class="btn btn-card">Continue Reading</a>
              <ul class="social d-flex gap-2">
                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog-details', $article->id)) }}" target="_blank"><img src="{{ url('frontend/img/Group 9640.png') }}"></a></li>
                <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog-details', $article->id))  }}&text={{ $article->title }}" target="_blank"><img src="{{ url('frontend/img/Group 9639.png') }}"></a></li>
                <li>
    <a href="javascript:void(0)" onclick="copyToClipboard('{{ route('blog-details', $article->id) }}')">
        <img src="{{ url('frontend/img/Group 9638.png') }}" alt="Copy Link">
    </a>
</li>  <li><a  href="https://mail.google.com/mail/?view=cm&fs=1&to=&su={{ urlencode($article->title) }}&body=Check out this article: {{ urlencode(route('blog-details', $article->id))  }}" target="_blank"><img src="{{ url('frontend/img/Group 9672.png') }}"></a></li>
              </ul>
            </div>
          </div>
          @endforeach
          @else
              <p>No blogs found.</p> <!-- Message for empty results -->
          @endif

        
           <!-- For Bootstrap 4 -->
          <!-- <nav aria-label="Page navigation example d-none">
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
          <nav aria-label="Page navigation example">
          @if($articleData->isNotEmpty() && $articleData->total() > 5)
            <ul class="pagination justify-content-center ">
                {{-- Previous Page Link --}}
                @if ($articleData->onFirstPage())
                    <li class="page-item disabled prev">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                        </a>
                    </li>
                @else
                    <li class="page-item prev">
                        <a class="page-link" href="{{ $articleData->previousPageUrl() }}">
                            <img src="{{ url('/public/frontend/img/Group 9650.png') }}" />
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($articleData->links()->elements[0] as $page => $url)
                    @if ($page == $articleData->currentPage())
                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($articleData->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $articleData->nextPageUrl() }}">
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
            @endif
</nav>

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
                    <a href="{{ url('/blog-details') }}/{{ $recentArticle->id  }}">
                      <img src="{{ $recentArticle->image ? url('public/article_images/' . $recentArticle->image) : url('frontend/img/Rectangle 78.png') }}" alt="Article Image">
                    </a>
                  </div>
                  <a href="{{ url('/blog-details') }}/{{ $recentArticle->id  }}">{{ $recentArticle->title }}</a>
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