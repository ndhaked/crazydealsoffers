@extends('layouts.app')
@section('title',$metaTitle)
@section('description',$metaDescription)
@section('content')
<main class="main-wrapper">
    <section class="product-list-sec">
        <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="title-main mt-3 mb-3">
                <h2 class="filter-title">
                    {{ ($search)?$search:'Latest Deals' }}           
                </h2>
            </div>
            <div class="product-list-tabs">
                <ul class="nav nav-tabs" id="menutabs" role="tablist">
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link @if(!$category) active @endif" onclick="paginate('{{route('products')}}',this)">All Products</a>
                    </li>
                    @if(count($categories)>0)
                        @foreach($categories as $list)
                        <li class="nav-item">
                            <a  href="javascript:;" class="nav-link @if($list->slug == $category) active @endif" onclick="paginate('{{route('category.products',$list->slug)}}',this)">{{ucfirst($list->name)}} </a>
                        </li>
                        @endforeach
                    @endif
                </ul>
                <div class="list-promotion-box">
                <figure>
                    <img src="{{ asset('/front/images/left-deals-list-promotion.svg') }}" alt="" class="list-promotion">
                    <div class="list-promotion-actions">
                    <a href="{{ @$socialLinkData['android-app-url']['value'] }}">
                        <img src="{{ asset('/front/images/icons/ic-google-play.svg') }} " alt="">
                    </a>
                    <a href="{{ @$socialLinkData['ios-app-url']['value'] }}">
                        <img src="{{ asset('/front/images/icons/ic-app-store.svg') }}" alt="">
                    </a>
                    </div>
                </figure>
                </div>
            </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="title-main my-3">
                    <h1 id="fullHeading" class="filter-title">
                        {{ ($fullHeading)?$fullHeading:'' }}           
                    </h1>
                </div>
                <div id="result">
                    @include('ajax_product_listing')
                </div>
            </div>
        </div>
        </div>
    </section>  
</main>
@endsection