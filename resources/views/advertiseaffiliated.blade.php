@extends('layouts.app')
@section('title',$affiliated['MetaTitle'])
@section('description',$affiliated['MetaDescription'])
@section('content')
<main class="main-wrapper">
    <div class="static-content">
        <div class="container">
            <div class="row">
            <div class="col-sm-12">
                <div class="title-main">
                <h3>
                    {{ $affiliated->title }}        
                </h3>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="image-static-data">
                    <div class="col-sm-12">
                        <img src="{{ $affiliated->S3Url }}" alt="">
                    </div>
                    <div class="col-sm-12">
                        <p class="margin-top-20">
                            {!! $affiliated->banner_description !!}
                        </p>
                    </div>
                </div>
                @if(isset($affiliated->image_1))
                    <div class="image-static-data">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-12">
                                    @if(isset($affiliated->image_1))
                                        <img src="{{ $affiliated->S3UrlImage2 }}" alt="">
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-12 padding-left-30">
                                    <p>
                                        {!! isset($affiliated->image_1)?$affiliated->description:'' !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(isset($affiliated->image_2))
                    <div class="image-static-data">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-12 padding-left-30 order-5">
                                @if(isset($affiliated->image_2))
                                    <img src="{{ $affiliated->S3UrlImage3 }}" alt="">
                                @endif
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <p>
                                        {!! (isset($affiliated->image_2))?$affiliated->description:'' !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="image-static-data">
                    <div class="col-sm-12">
                        <p>
                            {!! $affiliated->description !!}
                        </p>
                    </div>
                </div>
            </div>
            @if($affiliated->slug=='advertise')
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="mailto:{{ getConfig('market-email') }}" class="btn btn-primary width-btn">
                    Contact Us
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection