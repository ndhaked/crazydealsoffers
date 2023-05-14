@extends('admin.layouts.master')
@section('title', " ".'Edit Sub Admin'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-users"></i>
        {{trans('menu.role.subadmin')}} {{trans('menu.manager')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="Javascript:;">User Management</a></li>
        <li><a href="{{route('subadmin.index')}}">Suba Admin</a></li>
        <li class="active">Update Sub Admin</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Sub Admin</h3>
        </div>
         {!! Form::model($user,['method'=>'PATCH', 'route' => ['subadmin.update',$user->id],'class'=>'','id'=>'validateForm']) !!}
        {{ Form::hidden('id',null, []) }}
        <div class="box-body">
            @include('users::admin.subadmin.form')
        </div>
        <div class="box-footer">
            <div class="row pull-right">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">{{trans('menu.sidebar.update')}}</button>
                    <button type="reset" class="btn btn-default reset">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>
@endsection