@extends('admin.layouts.front_master')
@section('title', config('app.name').trans('menu.pipe').trans('menu.admin_reset_password'))
@section('content')
<div class="login-box">
      @if (session('error'))
          <div class="alert alert-error" role="alert">
              {{ session('error') }}
          </div>
      @endif
  <div class="login-box-body">
      <h1 class="admin-loginlogo">
        <img src="{{URL::to('img/logo.png')}}" width="100%" height="70" alt="{{config('app.name')}}" title="{{config('app.name')}}" />
       </h1>
        <form role="form" method="POST" action="{{ route('admin.password.request') }}" id="validateForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-group has-feedback ermsg">
         <input type="email" class="form-control uname" name="email" title="@lang('menu.validiation.please_enter_email_address')" required placeholder="@lang('menu.placeholder.email')" value="{{ old('email') }}" >
         @if ($errors->has('email'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('email') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback ermsg">
         <input type="password" class="form-control pword" name="password" placeholder="@lang('menu.placeholder.password')" title="@lang('menu.validiation.password_is_required')" id="password" required >
      </div>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }} has-feedback ermsg">
         <input type="password" class="form-control pword" name="password_confirmation" placeholder="@lang('menu.placeholder.confirm_password')" title="@lang('menu.validiation.confirm_password_is_required')" required >
      </div>
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('menu.reset_passowrd')</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection