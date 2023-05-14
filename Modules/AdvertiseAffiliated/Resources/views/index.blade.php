@extends('admin.layouts.master')
@section('title', " ".'Advertise Affiliated Management'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>

<section class="content-header">
    <h1><i class="fa fa-newspaper-o"></i>
        Advertise Affiliated Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Advertise Affiliated Management</li>
        <li class="active">Advertise Affiliated</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Advertise Affiliated</h3>
            <div class="box-tools pull-right">
                {{--<a href="###" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> Add Advertise Affiliated</a>--}}
                <br/>
            </div>
            <br><br>
            
        </div>
        <div class="box-body table-responsive" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Title</th>
                        <th>Banner Image</th>
                        <th>Left Image</th>
                        <th>Right Image</th>
                        <th>Descripiton</th>
                        <th>Created</th>
                        <!-- <th>@lang('users::menu.sidebar.form.status')</th> -->
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
        ajax: "{!! route('advertiseaffiliated.ajaxdata') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'title', name: 'title' },
            { data: 'banner_image', image: 'banner_image' },
            { data: 'image_1', image: 'image_1' },
            { data: 'image_2', image: 'image_2' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' },
            // { data: 'status', name: 'status' },
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
