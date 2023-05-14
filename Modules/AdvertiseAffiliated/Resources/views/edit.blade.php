@extends('admin.layouts.master')
@section('title', " ".'Edit '.$advertiseaffiliated->title." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-film"></i>
    {{ $advertiseaffiliated->title }} Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <!-- <li><a href="{{route('advertiseaffiliated.index')}}">{{ $advertiseaffiliated->title }}</a></li> -->
        <li class="active">Edit {{ $advertiseaffiliated->title }} </li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Edit {{ $advertiseaffiliated->title }}</h3>
        </div>
         {!! Form::model($advertiseaffiliated,['method'=>'PATCH', 'route' => ['advertiseaffiliated.update',$advertiseaffiliated->slug],'class'=>'','id'=>'validateForm']) !!}
        {{ Form::hidden('id',null, []) }}
        <div class="box-body">
            @include('advertiseaffiliated::form')
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