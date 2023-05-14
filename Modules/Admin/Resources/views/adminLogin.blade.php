@extends('admin.layouts.front_master')
@section('title', config('app.name').' | Login')
@section('content')
<div class="login-box">
      @if (session('error'))
          <div class="alert alert-error" role="alert">
              {{ session('error') }}
          </div>
      @endif
  <div class="login-box-body">
       <h1 class="admin-loginlogo">
        <img src="{{URL::to('img/logo.png')}}" width="100%" height="90" alt="logo" />
       </h1>
        <form role="form" method="POST" action="{{ route('admin.auth') }}" id="validateForm">
        @csrf
      <div class="form-group has-feedback ermsg">
         <input type="email" class="form-control uname" name="email" title="Email is required!" required placeholder="Email" value="{{ old('email') }}" >
      </div>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('email') }}</strong>
            </span>
        @endif
      <div class="form-group has-feedback ermsg">
         <input type="password" class="form-control pword" name="password" placeholder="Password" title="Password is required!" required >
      </div>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
          <label class=" control-label">{{trans('menu.sidebar.users.form.captcha')}} <span class="asterisk">*</span></label>
          <span class="ermsg" >
                 {!! NoCaptcha::display(['data-theme' => 'light']) !!}
              @if ($errors->has('g-recaptcha-response'))
              <span class="help-block">
                  <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
              </span>
              @endif
          </span>
        </div>
      <div class="row">
         <div class="col-xs-12 social-auth-links text-center">
          <button type="submit" class="btn btn-primary btn-block btn-flat formsubmit">Sign In</button>
        </div> 
      </div>
    </form>
     <a href="{{ route('admin.password.request') }}">I forgot my password</a>
  </div>
</div>
@endsection
@section('uniquePageScript')
{!! NoCaptcha::renderJs() !!}
@endsection
