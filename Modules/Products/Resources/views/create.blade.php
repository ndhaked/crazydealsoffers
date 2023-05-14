@extends('admin.layouts.master')
@section('title', " ".'Add Product'." - " .app_name(). " :: Admin")
@section('content')
<link rel="stylesheet" href="{{URL::to('tokeninput/styles/token-input.css')}}" type="text/css" />
<link rel="stylesheet" href="{{URL::to('tokeninput/styles/token-input-facebook.css')}}" type="text/css" />
    
<section class="content-header">
    <h1><i class="fa fa-cube"></i>
        {{trans('menu.role.products')}} Management
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('product.index')}}">{{trans('menu.role.products')}}</a></li>
        <li class="active">Add Product</li>
    </ol>
</section>
<section class="content">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('products::menu.sidebar.create')}} </h3>
        </div>
        {!! Form::open(['route' => 'product.store','class'=>'validateForm','id'=>'validateForm','files'=>true]) !!}
        <div class="box-body">
            @include('products::form')
        </div>
        <div class="box-footer">
            <div class="row pull-right">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary formsubmit">{{trans('menu.sidebar.create')}}</button>
                    <button type="reset" class="btn btn-default">{{trans('menu.sidebar.reset')}}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
</section>
@endsection
@section('script')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="{{URL::to('tokeninput/src/jquery.tokeninput.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() { 
    
    $("#SearchByName").autocomplete({  
        source: "{{route('product.getSuggessionDeals')}}",
        minLength: 2,
        select: function(event, ui) {
            if(ui.item.id==''){ 
                $("#SearchByName").val('');
                $("#product_id").val('');
                setTimeout(function(){ 
                    $("#SearchByName").val('');
                    $("#product_id").val('');
                }, 100);
            }else{
                $("#SearchByName").val(ui.item.value);
                $("#product_id").val(ui.item.id);  
            }
        }
    }).data("ui-autocomplete")._renderItem = function( ul, item ) {
             return $( "<li class='ui-autocomplete-row'></li>" )
            .data( "item.autocomplete", item )
            .append( item.label )
            .appendTo( ul );
    };

    $("#SearchByName").keyup(function(){
      if($(this).val()==''){
        $("#product_id").val('');
      }
    });
});
</script>
@endsection