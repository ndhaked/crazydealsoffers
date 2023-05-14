@extends('admin.layouts.master')
@section('title', " ".'Report Management'." ".trans('menu.pipe')." " .app_name(). " ".trans('menu.pipe').trans('menu.admin'))
@section('content')
<section class="content-header">
  <h1><i class="fa fa-dashboard"></i>
        Report Management
        <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}"><i class="fa fa-dashboard"></i> @lang('menu.home')</a></li>
    <li class="active">@lang('menu.sidebar.dashboard')</li>
  </ol>
</section>
<section style="padding: 15px 15px 0px 15px;">
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">Filter</h3>
        </div>
        <div class="box-body" id="result">
            @include('statistics::search_filter')
        </div>
    </div>
</section>
<section class="content" style="min-height: 100px;">
	<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Top User & Products Deck</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body no-padding">
          	<div class="row">
		        	<div class="col-lg-6 col-xs-6">
			          <div class="small-box bg-aqua">
			            <div class="inner">
			              <h3>{{$data['countTotalUsers']}}</h3>
			              <p>Total Customers</p>
			            </div>
			            <div class="icon">
			              <i class="ion ion-person-add"></i>
			            </div>
			            <a href="{{route('users.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			          </div>
			        </div>
			        <div class="col-lg-6 col-xs-6">
			          <div class="small-box bg-yellow">
			            <div class="inner">
			              <h3>{{$data['countTotalProducts']}}</h3>
			              <p>Total Products</p>
			            </div>
			            <div class="icon">
			              <i class="ion ion-cube"></i>
			            </div>
			            <a href="{{route('product.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			          </div>
			        </div>
						</div>
      	</div>
    </div>
    <div class="row">
      <div class="col-md-6">
					<div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Top 5 Products</h3>
	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	              </div>
	            </div>
	            <div class="box-body no-padding">
				    		<figure class="highcharts-figure">
							    <div id="products-top-5"></div>
							    <p class="highcharts-description">
							    </p>
								</figure>
	          	</div>
	        </div>
	    </div>
	    <div class="col-md-6">
					<div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Top 5 Users</h3>
	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	              </div>
	            </div>
	            <div class="box-body no-padding">
				    		<figure class="highcharts-figure">
							    <div id="users-top-5"></div>
							    <p class="highcharts-description">
							    </p>
								</figure>
	          	</div>
	        </div>
	    </div>
	    
	</div>
</section>
@endsection
@section('uniquePageScript')
<script src="https://code.highcharts.com/highcharts.js"></script> 
<script src="{!! Module::asset('statistics:js/statistics.js') !!}"></script>
<script type="text/javascript">
var countTotalProducts = <?php echo $data['countTotalProducts']; ?>;
var countTotalUsers = <?php echo $data['countTotalUsers']; ?>;
var top5Products  = <?php echo $data['top5Products']; ?>;
var top5Users = <?php echo $data['top5Users']; ?>;

$(document).ready(function(){

		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

    $("#searchFilter").change(function (e) {
        e.preventDefault();
				var search = $(this).val();
        
				var ajaxurl = "{!! route('statistics.index') !!}";
				window.location.href=ajaxurl+'?search='+search;
				// var type = "GET";
    //     $.ajax({
    //         type: type,
    //         url: ajaxurl,
    //         data:{search:search},
            
    //         success: function (data) {
    //             console.log(data);
    //         },
    //         error: function (data) {
    //             console.log(data);
    //         }
    //     });
    });
});

</script>
<?php /* <script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
*/ ?>
@endsection