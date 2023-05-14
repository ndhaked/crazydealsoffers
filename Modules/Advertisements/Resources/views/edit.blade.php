@extends('admin.layouts.master')
@section('title', " ".'Edit Advertisement'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-newspaper-o"></i>
        Advertisement Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('advertisement.index')}}">Advertisements</a></li>
        <li class="active">Edit Advertisement</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Advertisement</h3>
        </div>
         {!! Form::model($advertisements,['method'=>'PATCH', 'route' => ['advertisement.update',$advertisements->id],'class'=>'form-horizontal','id'=>'validateForm']) !!}
        {{ Form::hidden('id',null, []) }}
        <div class="box-body">
            <div class="row">
               <div class="col-md-12">
                 @include('advertisements::form')
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