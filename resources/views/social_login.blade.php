@extends('layouts.app')
@section('content')

<main class="main-wrapper">
    <div class="static-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-main">
                    <h3>Social Login</h3>
                    </div>
                </div>
            </div>

          
            <div class="row">
                    <div class="col-sm-5">
                    <a href="{{ route('fbLogin') }}"><img src="{{ asset('/assets/img/facebook-login.png') }}" style="width:350px" alt="Facebook login button png" /></a>
                    </div>
                    <div class="col-sm-2">
                    <a href="{{$pinterest_redirect_uri}}" ><img src="{{ asset('/assets/img/pinterest-logo.png') }}" style="height:77px;margin-top:28px" alt="Facebook login button png" /></a>
                    </div>
                    <!-- <div class="col-sm-4">
                    <a href="{{$instagram_redirect_uri}}" ><img src="{{ asset('/assets/img/instagram-icon.png') }}" style="height:70px;margin-top:30px" alt="Facebook login button png" /></a>
                    
                    </div> -->
            </div>
            <div class="row">
                    
            </div>
            <div class="row">
                   
            </div>
           
         
          

        </div>
    </div>
</main>

@endsection