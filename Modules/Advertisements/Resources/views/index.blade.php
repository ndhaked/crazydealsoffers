@extends('admin.layouts.master')
@section('title', " ".'Advertisements Management'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>

<section class="content-header">
    <h1><i class="fa fa-newspaper-o"></i>
        Advertisements Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Advertisements Management</li>
        <li class="active">Advertisements</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Advertisements</h3>
            <div class="box-tools pull-right">
                {{--<a href="{{route('advertisement.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> Add Advertisement</a>--}}
                <br/>
            </div>
            <br><br>
            
        </div>
        <div class="box-body table-responsive" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Page</th>
                        <th>Image</th>
                        <th>Created</th>
                        <th>@lang('users::menu.sidebar.form.status')</th>
                        <th>@lang('users::menu.sidebar.form.action')</th>
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
       processing: true,
        serverSide: true,
        ajax: "{!! route('advertisement.ajaxdata') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'page', name: 'page' },
            { data: 'image', image: 'image' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]
    });
  // Chosen Select
    jQuery("select").chosen({
      'width': '75px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    }); 
  });
</script>
@endsection
