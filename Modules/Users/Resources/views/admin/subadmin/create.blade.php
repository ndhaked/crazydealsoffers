@extends('admin.layouts.master')
@section('title', " ".'Create Sub Admin'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-users"></i>
        {{trans('menu.role.subadmin')}} {{trans('menu.manager')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="Javascript:;">User Management</a></li>
        <li><a href="{{route('subadmin.index')}}">{{trans('menu.role.subadmin')}}</a></li>
        <li class="active">Create Sub Admin</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('users::menu.sidebar.add_new_subadmin')}} </h3>
        </div>
        {!! Form::open(['route' => 'subadmin.store','class'=>'','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
            @include('users::admin.subadmin.form')
        </div>
        <div class="box-footer">
            <div class="row pull-right">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">{{trans('menu.sidebar.create')}}</button>
                    <button type="reset" class="btn btn-default reset">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>
@endsection