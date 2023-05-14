@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.email.slug')." - " .app_name(). " :: Admin")
@section('content')
    <section class="content-header">
      <h1><i class="{{trans('emailtemplates::menu.font_icon')}}"></i>
        {{trans('emailtemplates::menu.sidebar.manage')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">{{trans('emailtemplates::menu.sidebar.manage')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('emailtemplates::menu.sidebar.manage')}}</h3>

          <div class="box-tools pull-right">
            @if(Auth::user()->hasRole(['admin']) || Auth::user()->hasAnyPermission(['email-templates.create']))
            <!-- <a href="{{route('email-templates.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i>&nbsp;&nbsp;{{trans('menu.sidebar.email.add_new')}}</a> -->
            @endif
          </div>
        </div>
        <div class="box-body table-responsive" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
              <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Subject</th>
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
       "pagingType": "simple_numbers",
       processing: true,
        serverSide: true,
        ajax: "{!! route('email-templates.ajaxdata') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'name', name: 'name' },
            { data: 'subject', name: 'subject' },
            { data: 'body', name: 'body' },
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