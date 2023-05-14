@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.permission.slug')." - " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="fa fa-unlock"></i>
        {{trans('menu.sidebar.permission.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">{{trans('menu.sidebar.permission.slug')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.permission.slug')}}</h3>
        </div>
        <div class="box-body table-responsive" style="display: block;">
            <table class="table table-bordered table-hover"  id="data_filter">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th >Title</th>
                  <th >Group Name</th>
                  <th >Routing</th>
                  <th >Description</th>
                  <th >Action</th>
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
        ajax: '{!! route('permissions.ajaxdata') !!}',
        columns: [            
            { data: 'rownum', name: 'rownum' },
            { data: 'display_name', name: 'display_name' },
            { data: 'group_name', name: 'group_name' },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action', orderable:false, searchable:false },            
        ]
    });
    // Chosen Select
    jQuery("select").chosen({
      'min-width': '100px',
      'white-space': 'nowrap',
      disable_search_threshold: 10
    }); 
  });
</script>
@endsection