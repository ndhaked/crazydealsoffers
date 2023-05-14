@extends('admin.layouts.master')
@section('title', " ".'Upload CSV'." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
    <h1><i class="fa fa-cube"></i>
        {{trans('menu.role.products')}} Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('product.index')}}">{{trans('menu.role.products')}}</a></li>
        <li class="active">Upload Product CSV</li>
    </ol>
</section>
<section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Product</h3>
          <div class="box-tools pull-right">
            <a href="{{route('product.samplecsv')}}" class="btn btn-success btn-sm pull-right mr-1" style="margin-right:10px;"><i class="fa fa-file-excel-o"></i> Download Sample CSV</a>
          </div>  
        </div>
        {!! Form::open(['route' => 'product.importcsv','class'=>'form-horizontal','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
          <div class="row">
               <div class="col-md-12">
                    <div class="panel-body">
                       <div class="form-group">
                          <label class="col-sm-2 control-label">Upload CSV <span class="asterisk">*</span></label>
                          <div class="col-sm-8 ermsg">
                              {{ Form::file('import_file', ['required', 'class'=>'form-control','id'=>'import_file','placeholder'=>'Upload CSV file','title'=>'Please select csv file','style'=>'border-color: #4e5157;']) }}
                          </div>
                       </div>
                    </div>
               </div>
            </div>
        </div>
      <div class="box-footer">
         <div class="row pull-right">
            <div class="col-sm-12">
               <button class="btn btn-primary formsubmit" type="submit">Import</button>
               <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
            </div>
         </div>
      </div>
      {!! Form::close() !!}
</section>

@endsection