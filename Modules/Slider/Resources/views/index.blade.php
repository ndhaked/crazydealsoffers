@extends('admin.layouts.master')
@section('title', " ".'Banners'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>

<section class="content-header">
    <h1><i class="fa fa-newspaper-o"></i>
    Banner Advertisements
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Banner Advertisements</li>
        <li class="active">Banner</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Banner</h3>
            <div class="box-tools pull-right">
                @if($sliderLimit < 5)
                    <a href="{{route('slider.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> Add Banner</a>
                @endif
                &nbsp;&nbsp;

                <div class='col-md-6' style="padding:1px;">
                    <div class="form-group">
                        <div class='input-group'>
                            <h3 class="box-title">Collapse the banners</h3>
                        </div>
                    </div>
                </div>
                

                <div class='col-md-2' style="padding:1px;">
                    <div class="form-group">
                        <div class='input-group'>
                        
                            <select id="sliderShow" class="form-control" onchange="sliderSetting(this.value);">
                                <option value="0" {{ (getConfig('slider'))?'selected':'' }}>Hide</option>
                                <option value="1" {{ (getConfig('slider'))?'selected':'' }}>Show</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
        <div class="box-body table-responsive" style="display: block;">
            <table class="table table-bordered table-hover" id="data_filter">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Tile</th>
                        <th>Image</th>
                        <th>Image Order</th>
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
        ajax: "{!! route('slider.ajaxdata') !!}",
        columns: [
            { data: 'rownum', name: 'rownum',orderable:false, searchable:false },
            { data: 'title', name: 'title' },
            { data: 'banner_image', image: 'banner_image' },
            { data: 'slider_order', image: 'slider_order' },
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


    function sliderSetting(status)
    {
        $.ajax({
            url: "{{ route('slider.hideshow') }}",
            type: 'POST',
            datatype: "html",
            data: { _token: '{{csrf_token()}}','status': status},
            success: function (data) {
                $(function () {
                    (function () {
                        Lobibox.notify('success', {
                            rounded: false,
                            delay: 10000,
                            delayIndicator: true,
                                position: "top right",
                            msg: "Banners setting updated successful"
                        });
                    })();
                });
            }
        }); 
    }
</script>
@endsection
