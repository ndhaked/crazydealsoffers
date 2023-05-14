@extends('admin.layouts.master')
@section('title', " ".'Blog Management'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>

<section class="content-header">
    <h1><i class="fa fa-film "></i>
        Blogs Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Blogs Management</li>
        <li class="active">Blogs</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Blogs</h3>
            <div class="box-tools pull-right">
                <a href="{{route('blog.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> Add Blog</a>
                <br/>
            </div>
            <br><br>
            
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Title</th>
                        <th>Image 1</th>
                        <th>Image 2</th>
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
        ajax: "{!! route('blog.ajaxdata') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'title', name: 'title' },
            { data: 'image_1', image_1: 'image_1' },
            { data: 'image_2', image_2: 'image_2' },            
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