@extends('admin.layouts.front_master')
@section('title', config('app.name') .trans('menu.pipe'). trans('menu.admin_reset_password'))
@section('content')
<div class="login-box">
      @if (session('error'))
          <div class="alert alert-error" role="alert">
              {{ session('error') }}
          </div>
      @endif 
      @if (session('success'))
          <div class="alert alert-success" role="alert">
              {{ session('success') }}
          </div>
      @endif
  <div class="login-box-body">
     <h1 class="admin-loginlogo">
        <img src="{{URL::to('img/logo.png')}}" width="100%" height="70" alt="{{config('app.name')}}" title="{{config('app.name')}}" />
       </h1>
        <form role="form" method="POST" action="{{ route('admin.password.email') }}" id="validateForm">
        @csrf
      <div class="form-group has-feedback ermsg">
         <input type="email" class="form-control uname" name="email" title="@lang('menu.validiation.please_enter_email_address')" required placeholder="Email" value="{{ old('email') }}" >
      </div>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('email') }}</strong>
            </span>
        @endif
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat formsubmit" title="@lang('menu.send_password_reset_link')">@lang('menu.send_password_reset_link')
          </button>
        </div>
      </div>
        <br />
    <div class="row">
        <div class="col-xs-12">
          <a class="btn btn-block btn-facebook btn-flat" href="{{ route('admin.login') }}" title="@lang('menu.back_to_login')">
            @lang('menu.back_to_login')
          </a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection