@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.dashboard')." ".trans('menu.pipe')." " .app_name(). " Admin")
@section('content')
    <section class="content-header">
      <h1>Statistic</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Dashbaord</a></li>
        <li class="active">Statistic</li>
      </ol>
    </section>
      <section class="content" style="min-height: 100px;">
      <div class="row">
        @if(auth('admin')->user()->hasAnyPermissionCustom(['users.index','subadmin.index'],'admin'))
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>{{$usersCount}}</h3>
                <p>Total Customers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{route('users.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner">
                <h3>{{$subAdminCount}}<sup style="font-size: 20px"></sup></h3>
                <p>Total Subadmin</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{route('subadmin.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        @endcan
        @can('product.index')
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$productCount}}</h3>
              <p>Total Products</p>
            </div>
            <div class="icon">
              <i class="fa fa-cube"></i>
            </div>
            <a href="{{route('product.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @endcan
        @can('blog.index')
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{$blogCount}}</h3>
              <p>Total Blogs</p>
            </div>
            <div class="icon">
              <i class="ion ion-ios-film-outline"></i>
            </div>
            <a href="{{route('blog.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @endcan
        
      </div>
      @can('advertisement.index')
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{$advertisementCount}}</h3>
              <p>Total Advertisements</p>
            </div>
            <div class="icon">
              <i class="fa fa-newspaper-o"></i>
            </div>
            <a href="{{route('advertisement.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
      @endcan
    </section>
@endsection
