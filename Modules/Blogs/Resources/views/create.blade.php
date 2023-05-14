@extends('admin.layouts.master')
@section('title', " ".'Add Blog'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-film"></i>
        Blogs Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('blog.index')}}">Blogs</a></li>
        <li class="active">Add Blog</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Add Blog </h3>
        </div>
        {!! Form::open(['route' => 'blog.store','class'=>'','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
            @include('blogs::form')
        </div>
        <div class="box-footer">
            <div class="row pull-right">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">{{trans('menu.sidebar.create')}}</button>
                    <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>

@endsection
@section('uniquePageScript')
@endsection