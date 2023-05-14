@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.users.customer.main')." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
    <h1><i class="{{trans('users::menu.font_icon')}} "></i>
        {{trans('menu.sidebar.users.customer.main')}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> <a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">{{trans('menu.sidebar.users.customer.main')}}</li>
        <li class="active">Customers</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('users::menu.sidebar.customers')</h3>
            <br><br>
            {!! Form::open(['route' => 'users.index','method' => 'GET']) !!}
            <div class="row">
                <div class='col-md-2'>
                    <div class="form-group">
                          {{ Form::text('name',@$_GET['name'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.search_by_name')]) }}
                    </div>
                </div>
                <div class='col-md-2'>
                  <div class="form-group">
                      <div class='input-group'>
                          {{ Form::text('email',@$_GET['email'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.email')]) }}
                      </div>
                  </div>
                </div>
                <!-- <div class='col-md-2'>
                  <div class="form-group">
                      <div class='input-group'>
                            {!! Form::select('country_id',[''=>'Select Country'],@$_GET['country_id'], array('class' => 'form-control','id'=>'country_id','title'=>'please select country','onclick'=>"this.setAttribute('value', this.value);")) !!}
                      </div>
                  </div>
                </div> -->
                <div class='col-md-2'>
                    <button type="submit" class="btn btn-success btn-flat pull-right btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-search"></i> {{ trans('users::menu.sidebar.form.search') }}
                    </button>
                </div>
                <div class='col-md-2'>
                    <a href="{{route('users.index')}}" class="btn btn-success btn-flat pull-left btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
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
                        <th>@sortablelink('name', trans('users::menu.sidebar.form.name'))</th>
                        <th>@sortablelink('email',trans('users::menu.sidebar.form.email'))</th>
                        <th>@lang('users::menu.sidebar.form.reg_date')</th>
                        <th>@lang('users::menu.sidebar.form.mob_number')</th>
                        <th>@lang('users::menu.sidebar.form.status')</th>
                        <th>@lang('users::menu.sidebar.form.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users)>0)
                        @php $i=0; @endphp
                        @foreach($users as $user)
                         @php $i++; @endphp
                        <tr>
                            <td>{{ $user->fullName }}</td>
                            <td><a href="mailto:{{ $user->email }}" class="tooltips" data-original-title="Send Email">{{ $user->email }}</a></td>
                            <td>{{ $user->created_at->format(\Config::get('custom.default_date_formate')) }}</td>
                            <td>{{ ($user->phone !='') ? $user->phone : 'N/A' }}</td>
                            <td>
                                @if($user->status == 1)
                                <span class="label label-success">@lang('users::menu.sidebar.form.active')</span>
                                @else
                                <span class="label label-danger">@lang('users::menu.sidebar.form.inactive')</span>
                                @endif
                            </td>
                            <td class="">
                                @if($user->status==0) 
                                     <a data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Active" rel="Active" name="{{route('users.status',$user->slug)}}" href="javascript:;" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="{{route('users.status',$user->slug)}}" data-refresh="no" data-reload="yes"><i class="fa fa-check" aria-hidden="true"></i></a>
                                @else
                                    <a data-toggle="tooltip" class="success tooltips"  title="Inactive"  rel="Inactive" name="{{route('users.status',$user->slug)}}" href="javascript:;" data-placement="top" onClick="return AjaxActionTableDrow(this);" data-title="Inative" data-action="{{route('users.status',$user->slug)}}" data-refresh="no" data-reload="yes"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                @endif
                                <a href="{{route('users.show',$user->slug)}}" class="bookingAction" data-toggle="tooltip" data-placement="top" title="View Details">
                                  <i class="fa fa-eye"></i>
                                </a>
                                <!-- <a href="{{ route('users.edit',[$user->slug]) }}" class="" data-toggle="tooltip" data-placement="top" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a> -->
                                
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="9" align="center">@lang('menu.no_record_found')</td></tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">
            {{ $users->appends($_GET)->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</section>
@endsection
