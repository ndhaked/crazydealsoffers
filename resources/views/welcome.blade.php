@extends('layouts.app')
@section('title','CN Deals & Coupons: Latest Offers, Coupon Codes, Discounts & Deals')
@section('description','Save more with Coupons, and browse a wide range of coupons from top brands at CN Deals & Coupons. Shop online with CN Deals to save big every day.')
@section('keywords','Save more with Coupons, and browse a wide range of coupons from top brands at CN Deals & Coupons. Shop online with CN Deals to save big every day.')
@section('content')
@if(getConfig('slider'))
    <section class="home-banner">
        <div class="container-fluid">
            <div class="row">
                <div class="home-banner-slider owl-theme owl-carousel">
                <div class="item download-slide" style="background: url(./front/images/home-banner-bg-1.png);">
                    <div class="container">
                    <div class="row">
                        <div class="col-sm-12 d-flex">
                        <div class="download-slide-left">
                            <img src="{{ asset('/front/images/home-banner-1-mobile.png') }}" alt="" class="img-fluid">
                        </div>
                        <div class="download-slide-right">
                            <div class="download-slide-right-top">
                            <span>
                                Never miss a deal!
                            </span>
                            <h3>
                                Download our <br>
                                Free App!
                            </h3>
                            <span>
                                Because paying full price is overrated!
                            </span>
                            </div>
                            <div class="home-slide-download-btn">
                                <a href="{{ @$socialLinkData['android-app-url']['value'] }}">
                                    <img src="{{ asset('/front/images/icons/ic-google-play.svg') }}" alt="">
                                </a>
                                <a href="{{ @$socialLinkData['ios-app-url']['value'] }}">
                                    <img src="{{ asset('/front/images/icons/ic-app-store.svg') }}" alt="">
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                    @foreach($sliders as $slider)
                        <div class="item">
                            <figure>
                                @if($slider->url)
                                    <a href="{{ $slider->url }}" target="_blank"><img src="{{ $slider->S3Url }}" alt=""></a>
                                @else
                                <a href="javascript:void(0);"><img src="{{ $slider->S3Url }}" alt=""></a>
                                @endif
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@else
    <section class="home-banner">
    </section>
@endif
@if(count($dealOfday)>0)
    <section class="deals-listing-sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-main">
                    <h3>
                        Deals of the day
                    </h3>
                    </div>
                    <ul class="deal-list five-items">
                        @foreach($dealOfday as $product)
                            <li>
                                <a href="{{ route('details',$product->slug) }}">
                                    <div class="deal-box">
                                        <figure>
                                        <img src="{{ $product->S3Url }}" alt="">
                                        <div class="deal-bacth">
                                            <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="">
                                        </div>
                                        @if($product->tag)
                                            <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$product->tag]) }}" alt="" class="deal-badge">
                                        @endif
                                        </figure>
                                        <div class="deal-box-content">
                                        <span>
                                            {{ $product->name }}
                                        </span>
                                        <strong>
                                            @if($product->price>0)
                                            $ {{ number_format($product->price,2) }}
                                            @endif
                                        </strong>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
<section class="newsletter-sec">
    <div class="newsletter-inner-sec">
        <div class="container">
            <div class="row">
            <div class="col-lg-8 offset-lg-2 col-sm-12">
                <div class="title-main text-white">
                <h3 class="margin-bottom-15">
                    <span>
                    Subscribe to our
                    </span>
                    Newsletter!
                </h3>
                <p>
                    All the hottest deals delivered straight to your inbox! 
                </p>
                </div>
                <form action="javascript:;" id="subscribe" name="subscribe" class="newsletter-form-sec" method="post" > 
                    @csrf
                    <input type="email" name="email" id="email" class="form-control" autocomplete="off" placeholder="Email Address*" required>
                    <button type="submit" class="btn-primary white-btn" id="myButton" name="myButton" value="Submit">Subscribe Now</button>
                </form>
            </div>
            </div>
        </div>
    </div>
</section>
<section class="deals-listing-sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-main">
                    <h3>
                        All Deals
                    </h3>
                </div>
                <ul class="deal-list owl-carousel owl-theme deal-list-slider">
                    @foreach($allDeals as $item)
                    <li>
                        <a href="{{ route('details',$item->slug) }}">
                            <div class="deal-box">
                            <figure>
                                <img src="{{ $item->S3Url }}" alt="Product Image" >
                                <div class="deal-bacth">
                                    @if($item->deal_of_the_day)
                                        <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="Deal of the day">
                                    @endif
                                </div>
                                @if($item->tag)
                                    <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$item->tag]) }}" alt="" class="deal-badge">
                                @endif
                            </figure>
                            <div class="deal-box-content">
                                <span>
                                    {{ $item->name }}
                                </span>
                                <strong>
                                    @if($item->price>0)
                                        ${{ number_format($item->price,2) }}
                                    @endif
                                </strong>
                            </div>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="text-center w-100 view-all-listing">
                    <a href="{{ route('products') }}" class="btn-primary margin-top-30">
                    View All
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@include('layouts.follow_us')
@endsection