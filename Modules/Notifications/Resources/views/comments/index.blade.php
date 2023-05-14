@extends('admin.layouts.master')
@section('title', " Comments Notifications - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="{{trans('notifications::menu.font_icon')}}"></i>
        Comments Notifications
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li class="active">Comments Notifications</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <br><br>
            {!! Form::open(['route' => 'commentnotifications.index','method' => 'GET']) !!}
            <div class="row">
                <div class='col-md-2'>
                    <div class="form-group">
                        {{ Form::text('name',@$_GET['name'], ['class'=>'form-control','placeholder'=>'Search by product name']) }}
                    </div>
                </div>
                <div class='col-md-2'>
                    <div class="form-group">
                        {{ Form::text('username',@$_GET['username'], ['class'=>'form-control','placeholder'=>'Search by username']) }}
                    </div>
                </div>
                <div class='col-md-2' style="padding:0px;">
                    <div class="form-group">
                        <div class='input-group'>
                            {{ Form::select('isread', [''=>'Filter By','read'=>'Read','unread'=>'Unread'], @$_GET['isread'] , ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class='col-md-2'>
                    <button type="submit" class="btn btn-success btn-flat pull-right btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-search"></i> {{ trans('users::menu.sidebar.form.search') }}
                    </button>
                </div>
                <div class='col-md-2'>
                    <a href="{{route('commentnotifications.index')}}" class="btn btn-success btn-flat pull-left btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
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
                        <th>Notification</th>
                        <th>Product Name</th>
                        <th>Created At</th>
                        <th>Commented By</th>
                        <!-- <th>Commented To</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($notifications)>0)
                    @php $i=0; @endphp
                    @foreach($notifications as $list)
                    @php $i++; @endphp
                    <tr @if($list->is_read==0) class="is_unread" @endif>
                        <td>{{ substr($list->title,0,20) }}</td>
                        <td>{{$list->product->name}}</td>
                        <td>{{ $list->created_at->format(\Config::get('custom.default_date_formate')) }}</td>
                        <td>
                            @if(@$list->comment->user)
                                <a href="{{route('users.show',$list->comment->user->slug)}}"> {{$list->comment->user->name}} @if($list->comment->user->hasRole('admin')|| $list->comment->user->hasRole('subadmin'))({{$list->comment->user->roles()->pluck('name')[0]}}) @endif</a>
                            @else
                                N/A
                            @endif
                        </td>
                        {{--
                            <td>
                                @if($list->user)
                                <a href="{{route('users.show',$list->user->slug)}}"> {{$list->user->name}} @if($list->user->hasRole('admin')|| $list->user->hasRole('subadmin'))({{$list->user->roles()->pluck('name')[0]}}) @endif</a>
                        @else
                        N/A
                        @endif
                        </td>
                        --}}
                        <td>
                            <a href="{{route('commentnotifications.productComments',$list->product->slug)}}" class="bookingAction" data-toggle="tooltip" data-placement="top" title="View Comments">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9" align="center">@lang('menu.no_record_found')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">
                {{ $notifications->appends($_GET)->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</section>
@endsection
@section('uniquePageScript')
@endsection