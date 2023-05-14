@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.role.edit')." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
  <h1><i class="fa fa-lock"></i>
    {{trans('menu.sidebar.role.main')}}
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
    <li><a href="{{route('roles.index')}}">{{trans('menu.sidebar.role.slug')}}</a></li>
    <li class="active">{{trans('menu.sidebar.role.edit')}}</li>
  </ol>
</section>
<section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.role.edit')}}</h3>
        </div>
         {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->slug],'class'=>'form-horizontal validate','id'=>'validateForm']) !!}
          {{ Form::hidden('id',null, []) }}
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                  <div class="row">
                       <div class="form-group col-sm-12">
                          <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.name')}} <span class="asterisk">*</span></label>
                          <div class="col-sm-10 ermsg">
                             <input class="form-control " value="{{$role->name}}" @if($role->name == 'admin') readonly disable @endif name="name" required  title="Please enter role name." id="name" placeholder="Role Name" type="text">
                             <div class="description"><small>The role name must be lowercase, no-spacing will be stored.</small></div>
                          </div>
                       </div>
                       <div class="form-group col-sm-12">
                          <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.display_name')}} <span class="asterisk">*</span></label>
                          <div class="col-sm-10 ermsg">
                             {{ Form::text('display_name',null, ['required','class'=>'form-control','id'=>'display_name','placeholder'=>'Displat Name','title'=>'Please enter display name.']) }}
                          </div>
                       </div>
                       <div class="form-group col-sm-12">
                          <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.description')}} <span class="asterisk">*</span></label>
                          <div class="col-sm-10 ermsg">
                             {{ Form::textarea("description", null, ['required','class' => 'form-control','title'=>"Please enter Description.",'id'=>"description",'placeholder'=>"Description"]) }}
                          </div>
                       </div>
                    </div>
               </div>
          </div>
      </div>
      <div class="box-footer">
        <div class="row pull-right">
           <div class="col-sm-12">
              <button class="btn btn-primary">{{trans('menu.sidebar.update')}}</button>
             <a href="{{route('roles.index')}}" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</a>
           </div>
        </div>
      </div>
    {!! Form::close() !!}
</section>
@endsection
