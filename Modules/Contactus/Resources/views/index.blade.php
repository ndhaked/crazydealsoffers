@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="{{trans($model.'::menu.font_icon')}}"></i>
        {{trans($model.'::menu.sidebar.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
          <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
          <li class="active">@lang($model.'::menu.sidebar.main')</li>
          <li class="active">@lang($model.'::menu.sidebar.slug')</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans($model.'::menu.sidebar.manage')}} </h3>
          <div class="box-tools pull-right">
            @can($model.'.create')
            <a href="{{route($model.'.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans($model.'::menu.sidebar.add_new')}}</a>
            @endcan
          </div>
        </div>
        <div class="box-body" style="display: block; word-break: break-all;">
            <table class="table table-bordered table-hover" id="data_filter">
              <thead>
                 <tr>
                    <th style="width:6%;">{{trans($model.'::menu.sidebar.form.s_no')}}</th>
                    <th style="width:10%;">{{trans($model.'::menu.sidebar.form.first_name')}}</th>
                    <th style="width:10%;">{{trans($model.'::menu.sidebar.form.last_name')}}</th>
                    <th style="width:10%;">{{trans($model.'::menu.sidebar.form.email')}}</th>
                    <th style="width:10%;">{{trans($model.'::menu.sidebar.form.phone')}}</th>
                    <th style="width:35%;">{{trans($model.'::menu.sidebar.form.message')}}</th>
                    <th style="width:10%;">{{trans($model.'::menu.sidebar.form.request_date')}}</th>
                    <th style="width:7%;">{{trans($model.'::menu.sidebar.form.action')}}</th>
                 </tr>
              </thead>
            </table>
        </div>
      </div>
    </section>
@endsection
@section('uniquePageScript')
<script>
jQuery(document).ready(function() {
    var loaderString = '<div class="ajaxloader" id="AjaxLoader"><div class="strip-holder"><div class="strip-1"></div><div class="strip-2">';
    loaderString += '</div><div class="strip-3"></div></div></div>';
    jQuery('#data_filter').dataTable({
    	sPaginationType: "full_numbers",
    	processing: true,
        serverSide: true,
        pageLength: 50,
        ajax: "{!! route($model.'.index') !!}",
        language: {          
          "processing": loaderString,
        },
        columns: [
            { data: 'rownum', name: 'rownum',orderable:true, searchable:false},
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'message', name: 'message' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]
    });

    //Chosen Select
    jQuery("select").chosen({
      'width': '70px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    });	
});
</script>
@endsection


