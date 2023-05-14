@extends('admin.layouts.master')
@section('title', " Resend Notification - " .app_name(). " :: Admin")
@section('content')
   <section class="content-header">
      <h1><i class="fa fa-bell"></i>
        Resend Notification
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('notifications.index')}}">Notifications</a></li>
         <li class="active">Resend Notification</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Resend Notification</h3>
        </div>
         {!! Form::model($data, ['method' => 'PATCH','route' => ['notifications.update', $data->id],'class'=>'form-horizontal validate','id'=>'validateForm']) !!}
             {{ Form::hidden('id',null, []) }}
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                  @include('notifications::basic.form')
                </div>
            </div>
      </div>
      <div class="box-footer">
         <div class="row pull-right">
             <div class="col-sm-12">
                 <button class="btn btn-primary" type="submit">Resend Notification</button>
                   <a href="{{route('notifications.index')}}" class="btn btn-default">{{trans('menu.sidebar.cancel')}}</a>
              </div>
          </div>
      </div>
      {!! Form::close() !!}
    </section>
@endsection
@section('uniquePageScript')
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<script>
jQuery(document).ready(function() {
    jQuery(".chosen-select").chosen({
        no_results_text: "Oops, nothing found!"
    })
    $('.reset').click(function(){
        $(".chosen-select").val('').trigger("chosen:updated");
    });
}); 
</script>
@endsection
