@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.role.slug')." - " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="fa fa-lock"></i>
        {{trans('menu.sidebar.role.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">{{trans('menu.sidebar.role.slug')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.role.slug')}}</h3>

          <div class="box-tools pull-right">
             <a href="{{route('roles.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans('menu.sidebar.role.add_new')}}</a>
          </div>
        </div>
        <div class="box-body table-responsive" style="display: block;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
              <tr>
                    <th>S.No.</th>
                    <th>Role Name</th>
                    <th>Display Name</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Action</th>
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
        serverSide: false,
        ajax: '{!! route('roles.ajaxdata') !!}',
        columns: [
            { data: 'rownum', name: 'rownum' },
            { data: 'name', name: 'name' },
            { data: 'display_name', name: 'display_name' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]
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
