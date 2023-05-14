@extends('admin.layouts.master')
@section('title', " ".trans($model.'::menu.sidebar.main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>

  <section class="content-header">
    <h1><i class="{{trans($model.'::menu.font_icon')}} "></i>
      {{trans($model.'::menu.sidebar.main')}}
      <small></small>
    </h1>
    <ol class="breadcrumb">
       <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
       <li class="active">@lang($model.'::menu.sidebar.menu_title')</li>
      <li class="active">@lang($model.'::menu.sidebar.slug')</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">{{trans($model.'::menu.sidebar.manage')}} </h3>
        <div class="box-tools pull-right">
        @can('categories.create')
          <a href="{{route($model.'.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans($model.'::menu.sidebar.add_new')}}</a>
          <br/>
        @endcan
        </div>
      </div>
      <div class="box-body table-responsive" style="display: block; word-break: break-all;">
          <table class="table table-bordered table-hover"  id="data_filter">
            <thead>
               <tr>
                  <th>@lang('menu.sn')</th>
                  <th>@lang('Category ID')</th>
                  <th>@lang($model.'::menu.sidebar.form.name')</th>
                  <th>Image</th>
                  <th>@lang($model.'::menu.sidebar.form.status')</th>
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
        serverSide: true,
        pageLength: 50,
        ajax: "{!! route($model.'.index') !!}",
        language: {          
          // "processing": loaderString,
        },
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'id', name: 'id'},
            { data: 'name', name: 'name' },
            { data: 'image', image: 'image' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ],
        "drawCallback": function( settings ) {
            $(".group1").colorbox({rel:'group1'}); //this use for image preview if nay else
        }
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

