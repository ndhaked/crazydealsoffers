@extends('layouts.app')
@section('title','Latest Information about Coupon codes & Deals | CN Deals & Coupons')
@section('description','CN Deals & Coupons Only Sell Coupons That Work! Real-time deals, promo codes, and offer updates from all offline and online stores!')
@section('content')
<main class="main-wrapper">
    <section class="blog-sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                <div class="title-main">
                    <h3>
                    Latest Blog              
                    </h3>
                </div>
                </div>
            </div>
            <div id="result">
                  @include('ajax_blog_listing')
            </div>
        </div>
    </section>
</main>
@endsection