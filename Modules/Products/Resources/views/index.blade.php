@extends('admin.layouts.master')
@section('title', " ".'Products Management'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/lightbox.min.css') }}">
<script src="{{ asset('public/js/lightbox-plus-jquery.min.js') }}"></script>

<section class="content-header">
    <h1><i class="fa fa-cube "></i>
        {{trans('menu.role.products')}} Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">@lang('menu.role.products') Management</li>
        <li class="active">@lang('menu.role.products')</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('menu.role.products')</h3>
            <div class="box-tools pull-right">
                <a href="{{route('product.create')}}" class="btn btn-success btn-sm pull-right "><i class="fa fa-plus"></i> {{trans('products::menu.sidebar.create')}}</a>
                <!-- <a href="{{route('product.exportcsv')}}" class="btn btn-success btn-sm pull-right mr-1" style="margin-right:10px;"><i class="fa fa-file-excel-o"></i> Export CSV</a> -->
                <a href="{{route('product.uploadcsv')}}" class="btn btn-success btn-sm pull-right mr-1" style="margin-right:10px;"><i class="fa fa-file-excel-o"></i> Import CSV</a>
                <a href="{{route('product.removeinactive')}}" class="btn btn-success btn-sm pull-right mr-1" style="margin-right:10px;"><i class="fa fa-times"></i> Remove Inactive Products</a>
                <br/>
            </div>
            <br><br>
            {!! Form::open(['route' => 'product.index','method' => 'GET']) !!}
            <div class="row">
                <div class='col-md-3'>
                    <div class="form-group">
                          {{ Form::text('name',@$_GET['name'], ['maxlength'=>80,'class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.search_by_name')]) }}
                    </div>
                </div>
                <div class='col-md-2'>
                  <div class="form-group">
                      <div class='input-group'>
                          {{ Form::text('coupon_code',@$_GET['coupon_code'], ['maxlength'=>50,'class'=>'form-control','placeholder'=>'Coupon code']) }}
                      </div>
                  </div>
                </div>
                <div class='col-md-2' style="padding:0px;">
                      <div class="form-group">
                          <div class='input-group'>
                              {{ Form::select('type', [''=>'All Deals','dealofday'=>'Deals of the Day'], @$_GET['type'] , ['class' => 'form-control']) }}
                          </div>
                      </div>
                </div>
                <div class='col-md-2'>
                    <button type="submit" class="btn btn-success btn-flat pull-right btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-search"></i> {{ trans('users::menu.sidebar.form.search') }}
                    </button>
                </div>
                 <div class='col-md-2'>
                    <a href="{{route('product.index')}}" class="btn btn-success btn-flat pull-left btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-refresh"></i> {{ trans('users::menu.sidebar.form.clear_search') }}
                    </a>
                 </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>@sortablelink('name', 'Name')</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>@sortablelink('coupon_code', 'Coupon Code')</th>
                        <th>Deals of the Day</th>
                        <th>Expiration Date</th>
                        <th>@lang('users::menu.sidebar.form.status')</th>
                        <th>@lang('users::menu.sidebar.form.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($products)>0)
                        @php $i=0; @endphp
                        @foreach($products as $product)
                         @php $i++; @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>@if($product->category) {{ $product->category->name }} @else N/A @endif</td>
                            <td><a class="" href="{{ $product->S3Url }}" data-lightbox="example-1"> <img class="" style="width: 60px;" src="{{ $product->S3Url }}"></a></td>
                            <td>{{ $product->coupon_code }}</td>
                            <td>
                                @if($product->deal_of_the_day==1) 
                                     <a data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Deal of the day" rel="Deal of the day" name="{{route('product.deal_of_the_day',[$product->id,0])}}" href="javascript:;" onClick="return AjaxActionTableDrow(this);" data-title="No deal of the day" data-action="{{route('product.deal_of_the_day',[$product->id,0])}}" data-refresh="no" data-reload="yes"><i class="fa fa-check" aria-hidden="true"></i></a>
                                @else
                                    <a data-toggle="tooltip" class="success tooltips"  title="No deal of the day"  rel="Active" name="{{route('product.deal_of_the_day',[$product->id,1])}}" href="javascript:;" data-placement="top" onClick="return AjaxActionTableDrow(this);" data-title="Deal of the day" data-action="{{route('product.deal_of_the_day',[$product->id,1])}}" data-refresh="no" data-reload="yes"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                @endif

                            </td>
                            <td>
                                <br>{{ $product->expiry_date->format(\Config::get('custom.default_date_time_formate')) }}</td>
                            <td>
                                @if($product->status == 'active')
                                <span class="label label-success">@lang('users::menu.sidebar.form.active')</span>
                                @else
                                <span class="label label-danger">Expired</span>
                                @endif
                            </td>
                            <td class="">
                                @if($product->status=='expired') 
                                     <a data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Active" rel="Active" name="{{route('product.status',$product->id)}}" href="javascript:;" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="{{route('product.status',$product->id)}}" data-refresh="no" data-reload="yes"><i class="fa fa-check" aria-hidden="true"></i></a>
                                @else
                                    <a data-toggle="tooltip" class="success tooltips"  title="Expired"  rel="Active" name="{{route('product.status',$product->id)}}" href="javascript:;" data-placement="top" onClick="return AjaxActionTableDrow(this);" data-title="Expired" data-action="{{route('product.status',$product->id)}}" data-refresh="no" data-reload="yes"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                @endif
                                <a href="{{ route('product.edit',[$product->id]) }}" class="" data-toggle="tooltip" data-placement="top" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                <form method="POST" action="{{ route('product.destroy',[$product->id]) }}" accept-charset="UTF-8" style="display:inline" class="dele_{{$product->id}}">
                                <input name="_method" value="DELETE" type="hidden">
                                    @csrf
                                    <span>
                                         &nbsp;<a href="javascript:;" id="dele_{{$product->id}}" data-toggle="tooltip" title="Delete" type="button"  data-placement="top" name="Delete" class="delete_action tble_button_st tooltips" Onclick="return ConfirmDeleteLovi(this.id,this.name,this.name);" ><i class="fa fa-trash-o" title="Delete"></i>
                                        </a>
                                     </span>
                                </form>
                                <a href="{{route('commentnotifications.productComments',$product->slug)}}" class="bookingAction" data-toggle="tooltip" data-placement="top" title="View Comments">
                                  <i class="fa fa-comment"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="9" align="center">@lang('menu.no_record_found')</td></tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">
                {{ $products->appends($_GET)->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</section>
@endsection