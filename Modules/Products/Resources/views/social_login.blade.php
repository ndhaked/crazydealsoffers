@extends('admin.layouts.master')
@section('title', " ".'Add Product'." - " .app_name(). " :: Admin")
@section('content')
<link rel="stylesheet" href="{{URL::to('tokeninput/styles/token-input.css')}}" type="text/css" />
<link rel="stylesheet" href="{{URL::to('tokeninput/styles/token-input-facebook.css')}}" type="text/css" />
    
<section class="content-header">
    <h1><i class="fa fa-cube"></i>
        {{trans('menu.role.products')}} Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('product.index')}}">{{trans('menu.role.products')}}</a></li>
        <li class="active">Add Product</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('products::menu.sidebar.social')}} </h3>
        </div>
        <div class="row">
                    <div class="col-sm-12">
                    <a href="{{ route('product.fbLogin') }}"><img src="{{ asset('/assets/img/facebook-login.png') }}" style="width:350px" alt="Facebook login button png" /></a>
                    </div>
                  
                    <!-- <div class="col-sm-4">
                    <a href="{{$instagram_redirect_uri}}" ><img src="{{ asset('/assets/img/instagram-icon.png') }}" style="height:70px;margin-top:30px" alt="Facebook login button png" /></a>
                    
                    </div> -->
            </div>  
            <div class="row">
                  
                    <div class="col-sm-12">
                    <a href="{{$pinterest_redirect_uri}}" ><img src="{{ asset('/assets/img/pinterest-logo.png') }}" style="height:77px;margin-top:28px;margin-left: 20px;" alt="Facebook login button png" />
                <br>
                <br>
                <br>
                </a>
                    
                </div>
                    <!-- <div class="col-sm-4">
                    <a href="{{$instagram_redirect_uri}}" ><img src="{{ asset('/assets/img/instagram-icon.png') }}" style="height:70px;margin-top:30px" alt="Facebook login button png" /></a>
                    
                    </div> -->
            </div>  
</section>
@endsection
