<div class="row">
    @if(count($blogs)>0)
        @foreach($blogs as $blog)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="blog-list-box">
                <figure>
                    <img src="{{ $blog->S3Url }}" alt="">
                </figure>
                <h3 class="blog-title">
                    {{ $blog->title }}
                </h3>
                <span>
                    {{ date_format($blog->created_at,"d F,Y - h:i A") }}
                </span>
                <div class="blog-desc">{!! $blog->description !!}</div><br>
                <a href="{{ route('blog.details',$blog->slug)}}" class="btn-text">
                    + READ MORE
                </a>
            </div>
        </div>
        @endforeach
    @else
        No Record Found
    @endif
</div>
<div class="row">
    <div class="col-sm-12">
        {!! $blogs->links('front_dash_pagination') !!}
    </div>
</div>