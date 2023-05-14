@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
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
       <!--  <a href="{{route($model.'.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans($model.'::menu.sidebar.add_new')}}</a> -->
         <br/>
      </div>
    </div>
    <div class="box-body table-responsive" style="display: block;">
        <table class="table table-bordered table-hover"  id="data_filter">
          <thead>
            <tr>
                <th>@lang('menu.sn')</th>
                <th>@lang($model.'::menu.sidebar.form.config_title')</th>
                <th>@lang($model.'::menu.sidebar.form.config_value')</th>
                <th>@lang('menu.created_at')</th>
                <th>@lang('menu.action')</th>
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
    jQuery('#data_filter').dataTable({
		sPaginationType: "full_numbers",
     "pagingType": "simple_numbers",
		processing: true,
        serverSide: false,
        ajax: "{!! route($model.'.index') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false},
            { data: 'config_title', name: 'config_title' },
            { data: 'config_value', name: 'config_value' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]	
    });
	// Chosen Select
    jQuery("select").chosen({
      'width': '70px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    });	
  });
</script>
@endsection

