@extends('admin.layouts.master')
@section('title', " ".'Customer Details'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
    <section class="content-header">
      <h1>@lang('users::menu.sidebar.form.customer_profile')</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('users.index')}}">{{trans('menu.role.customers')}}</a></li>
        <li class="active">@lang('users::menu.sidebar.form.customer_profile')</li>
        <li class="active">{{ucfirst($user->fullName)}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="box box-primary">
            <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="{{$user->ThumbPicturePath}}" alt="User profile picture" id="dash_PImage" onerror="imgError(this);">
              @if(!$user->hasRole('customer'))
                <span class="upload-icon"> <i class="fa fa-camera"></i>
                <input type="file" id="PImage" name="PImage" accept="image/*" class="onlyimageupload" data-userid="{{$user->id}}" data-uploadurl="{{route('users.uploadProfile')}}">
                </span>
              @endif
              <h3 class="profile-username text-center">{{ucfirst($user->fullName)}}</h3>
              <p class="text-muted text-center">
              @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <label class="badge badge-success"> {{ $v }}</label>
                @endforeach
              @endif
              </p>
            </div>
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('users::menu.sidebar.form.address')</h3>
            </div>
            <div class="box-body">
              <strong><i class="fa fa-map-marker margin-r-5"></i></strong>
              @if(isset($user->address))
                {{ucfirst($user->address)}} 
              @else
              N/A
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#userDetails" data-toggle="tab">@lang('users::menu.sidebar.form.user_details')</a></li>
              <!-- <li><a href="#changePassword" data-toggle="tab">@lang('users::menu.sidebar.form.change_password')</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="userDetails">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.first_name'):</label>
                    <div class="col-sm-9 paddt7">
                       {{ucfirst($user->name)}}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.last_name'):</label>
                    <div class="col-sm-9 paddt7">
                       {{ucfirst($user->name)}}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.email'):</label>
                     <div class="col-sm-9 paddt7">
                       {{$user->email}}
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.mob_number'):</label>
                     <div class="col-sm-9 paddt7">
                      {{$user->phone}}
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="inputCountry" class="col-sm-3 control-label"> @lang('users::menu.sidebar.form.status'):</label>
                      <div class="col-sm-9 paddt7">
                       @if($user->status==1)
                          <span class="label label-success">Active</span>
                       @else
                          <span class="label label-danger">InActive</span>
                       @endif
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="inputCountry" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.email_verify'):</label>
                      <div class="col-sm-9 paddt7">
                       @if($user->email_verified_at)
                          <span class="label label-success">Verified</span>
                       @else
                          <span class="label label-danger">Not Verified</span>
                       @endif
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="inputCountry" class="col-sm-3 control-label">@lang('users::menu.sidebar.form.date_of_join'):</label>
                    <div class="col-sm-9 paddt7">
                       {{$user->created_at->format(\Config::get('custom.default_date_time_formate'))}}
                    </div>
                  </div>
                </form>
              </div>
              <!-- <div class="tab-pane" id="changePassword">
                @include('users::change_password')
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
