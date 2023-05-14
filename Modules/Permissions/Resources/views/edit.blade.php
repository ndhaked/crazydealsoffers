@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.role_permission.assign_permission')." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
  <h1><i class="fa fa-unlock"></i>
    {{trans('menu.sidebar.role_permission.main')}}
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
     <li><a href="{{route('permissions.index')}}">{{trans('menu.sidebar.permission.slug')}}</a></li>
     <li class="active">{{trans('menu.sidebar.update')}}</li>
  </ol>
</section>
<section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.permission.edit')}}</h3>
        </div>
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                {!! Form::model($permission, ['method' => 'PATCH','route' => ['permissions.update', $permission->slug],'class'=>'form-horizontal','id'=>'validateForm']) !!}
                    <div class="row">
                      <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.display_name')}} <span class="asterisk">*</span></label>
                        <div class="col-sm-10 ermsg">
                          <input class="form-control"  name="display_name" required  title="Please enter permission display name." value="{{ $permission->display_name }}"  id="name" placeholder="Display Name" type="text">
                        </div>
                      </div>    
                      <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.group_name')}} <span class="asterisk">*</span></label>
                        <div class="col-sm-10 ermsg">
                          {!!Form::select('group_name', $permissionGroups, null, ['placeholder'=>'Select group name.', 'title'=>'Please select group name.', 'class' => 'form-control',  'required'=>'required' ])!!}
                        </div>
                      </div>
                      <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.permission_name')}} <span class="asterisk">*</span></label>
                        <div class="col-sm-10 ermsg">
                         {!!Form::select('name', $routes, null, ['disabled' => true,'title'=>'Please select permission name.', 'class' => 'selectpicker form-control', 'data-show-subtext'=>true, 'data-show-subtext'=>true, 'data-live-search'=>true, 'required'=>'required' ])!!}
                        </div>
                      </div>
                      <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label">{{trans('menu.sidebar.role.form.description')}} <span class="asterisk"></span></label>
                        <div class="col-sm-10 ermsg">
                            <textarea class="form-control"  name="description" title="Please enter description." id="description" placeholder="Desrrption" type="text">{!! $permission->description !!}</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="box-footer">
                      <div class="row pull-right">
                         <div class="col-sm-12">
                            <button class="btn btn-primary">{{trans('menu.sidebar.update')}}</button>
                           <a href="{{route('permissions.index')}}" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</a>
                         </div>
                      </div>
                    </div>
                  {!! Form::close() !!}
              </div>
          </div>
      </div>
</section>
@endsection