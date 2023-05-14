@extends('admin.layouts.master')
@section('title', " ".'Edit Banner'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-newspaper-o"></i>
        Banner Advertisements
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('slider.index')}}">Banner</a></li>
        <li class="active">Edit Banner</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Banner</h3>
        </div>
         {!! Form::model($slider,['method'=>'PATCH', 'route' => ['slider.update',$slider->id],'class'=>'form-horizontal','id'=>'validateForm']) !!}
        {{ Form::hidden('id',null, []) }}
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
                    <button type="submit" class="btn btn-primary">{{trans('menu.sidebar.update')}}</button>
                    <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>
@endsection