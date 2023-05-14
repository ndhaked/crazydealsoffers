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
         <li class="active">@lang($model.'::menu.sidebar.menu_title') @lang('menu.manager')</li>
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
             <br/>
          </div>
        </div>
        <div class="box-body table-responsive" style="display: block; word-break: break-all;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
                 <tr>
                    <th>@lang('menu.sn')</th>
                    <th>@lang($model.'::menu.sidebar.form.question')</th>
                    <th>Answer</th>
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
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'question', name: 'question' },
            { data: 'answer', name: 'answer' },
            { data: 'action', name: 'action', orderable:false, searchable:false  },
        ],
        "drawCallback": function( settings ) {
            $(".group1").colorbox({rel:'group1'}); //this use for image preview if nay else
        }
    });
	  //Chosen Select
    jQuery("select").chosen({
      'min-width': '100px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    });	
  });
</script>
@endsection

