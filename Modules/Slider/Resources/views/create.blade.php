@extends('admin.layouts.master')
@section('title', " ".'Add Banner'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-newspaper-o"></i>
    Banner Advertisements
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('slider.index')}}">Banner</a></li>
        <li class="active">Add Banner</li>
    </ol>
</section>
<section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Banner</h3>
        </div>
        {!! Form::open(['route' => 'slider.store','class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                 @include('slider::form')
               </div>
            </div>
        </div>
      <div class="box-footer">
         <div class="row pull-right">
            <div class="col-sm-12">
               <button class="btn btn-primary formsubmit" type="submit">{{trans('menu.sidebar.create')}}</button>
               <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
            </div>
         </div>
      </div>
      {!! Form::close() !!}
    </section>

@endsection