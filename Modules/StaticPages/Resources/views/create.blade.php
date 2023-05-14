@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.create')." ".trans('menu.pipe')." " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="{{trans($model.'::menu.font_icon')}}"></i>
        {{trans($model.'::menu.sidebar.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route($model.'.index')}}">@lang($model.'::menu.sidebar.main') @lang('menu.manager')</a></li>
         <li class="active">{{trans($model.'::menu.sidebar.create')}}</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans($model.'::menu.sidebar.create')}}</h3>
        </div>
          {!! Form::open(['route' => $model.'.store','class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                 @include($model.'::basic.form')
               </div>
            </div>
      </div>
      <div class="box-footer">
         <div class="row pull-right">
            <div class="col-sm-12">
               <button class="btn btn-primary" type="submit">{{trans('menu.sidebar.create')}}</button>
               <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
            </div>
         </div>
      </div>
      {!! Form::close() !!}
    </section>
@endsection
