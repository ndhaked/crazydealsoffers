@extends('layouts.app')
@section('title',$blog['MetaTitle'])
@section('description',$blog['description'])
@section('content')
<main class="main-wrapper">
    <section class="blog-sec blog-detail-page">
        <div class="element"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                <ul class="blog-detail-slider owl-carousel owl-theme">
                    @if($blog->image_1)
                        <li class="item">
                            <figure>
                                <div class="blog-detail-img-blog">
                                    <img src="{{ $blog->S3Url }}" alt="">
                                </div>
                            </figure>
                            <div class="blog-detail-top-info">
                                <div class="blog-detail-top-box">
                                    <h3>
                                        {{ $blog['title'] }}
                                    </h3>
                                    <span>
                                        {{ date_format($blog->created_at,"d F,Y - h:i A") }}
                                    </span>
                                </div>
                                {!! $blog['description'] !!}
                            </div>
                        </li>
                    @endif
                    @if($blog->image_2)
                        <li class="item">
                            <figure>
                                <div class="blog-detail-img-blog">
                                    <img src="{{ $blog->S3UrlImage2 }}" alt="">
                                </div>
                            </figure>
                            <div class="blog-detail-top-info">
                                <div class="blog-detail-top-box">
                                    <h3>
                                        {{ $blog['title'] }}
                                    </h3>
                                    <span>
                                        {{ date_format($blog->created_at,"d F,Y - h:i A") }}
                                    </span>
                                </div>
                                {!! $blog['description'] !!}
                            </div>
                        </li>
                    @endif
                    @if($blog->video_link)
                        <li class="item">
                            <figure>
                                <div class="blog-detail-img-blog">
                                    <iframe src="{{ $blog->video_link }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </figure>
                            <div class="blog-detail-top-info">
                                {!! $blog['description'] !!}
                            </div>
                        </li>
                    @endif
                </ul>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection