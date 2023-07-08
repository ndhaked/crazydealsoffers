@extends('layouts.app')
@section('title',config('app.name').': Latest Offers, Coupon Codes, Discounts & Deals')
@section('description','Save more with Coupons, and browse a wide range of coupons from top brands at Crazy Deals & Coupons. Shop online with Crazy Deals & Coupons to save big every day.')
@section('keywords','Save more with Coupons, and browse a wide range of coupons from top brands at Crazy Deals & Coupons')
@section('content')
@if(getConfig('slider'))
    <section class="home-banner">
        <div class="container-fluid">
            <div class="row">
                <div class="home-banner-slider owl-theme owl-carousel">
					<div class="item" >
					   <img src="{{ asset('/front/images/banner1.jpg') }}"> 
					</div>
					<div class="item" >
					   <img src="{{ asset('/front/images/banner2.jpg') }}"> 
					</div>
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
										<div class="deal-source ribbon"><span>Amazon</span></div>
                                        <figure>
                                        <img src="{{ $product->S3Url }}" alt="">
                                        <?php /*
                                        <div class="deal-bacth">
                                            <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="">
                                        </div>
                                        @if($product->tag)
                                            <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$product->tag]) }}" alt="" class="deal-badge">
                                        @endif
                                        */?>
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

@include('categoires_block')

<section class="newsletter-sec">
    <div class="container">
        <div class="newsletter-box">
            <div class="newsletter-inner-sec">
                <div class="title-main text-white">
                <h3 class="margin-bottom-15">
                    <span>
                    Subscribe to our
                    </span>
                    Newsletter!
                </h3>
                <p>
                    All the hottest deals delivered straight to your inbox! 
                    <?php /*
                    Stay connected with us by subscribing to our newsletter! Receive exclusive updates, promotions, and news straight to your inbox. Don't miss out on the latest trends and exciting offers – subscribe now! 
                    */ ?>
                </p>
                </div>
                <form action="javascript:;" id="subscribe" name="subscribe" class="newsletter-form-sec" method="post" > 
                    <div class="newsletter-inner">
                        @csrf
                        <input type="email" name="email" id="email" class="form-control" autocomplete="off" placeholder="Email Address*" required>
                        <button type="submit" class="btn-primary white-btn" id="myButton" name="myButton" value="Submit">Subscribe Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@include('all_deals_block')
@endsection