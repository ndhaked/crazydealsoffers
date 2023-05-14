@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.email.create')." - " .app_name(). " :: Admin")
@section('content')

   <section class="content-header">
      <h1><i class="fa fa-envelope"></i>
        {{trans('menu.sidebar.email.main')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li><a href="{{route('email-templates.index')}}">{{trans('menu.sidebar.email.slug')}}</a></li>
         <li class="active">{{trans('menu.sidebar.email.create')}}</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{trans('menu.sidebar.email.create')}}</h3>
        </div>
        {!! Form::open(['route' => 'email-templates.store','class'=>'form-horizontal','id'=>'validateForm']) !!}
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
                        <button class="btn btn-primary" type="submit">{{trans('menu.sidebar.create')}}</button>
                        <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                     </div>
              </div>
          </div>
        {!! Form::close() !!}
    </section>
@endsection
@section('uniquePageScript')
<script type="text/javascript">
    $('form').on('reset', function(e) {
        $("#product_id").val('');
    });
</script>
@endsection