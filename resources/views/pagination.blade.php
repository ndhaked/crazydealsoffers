@if ($paginator->hasPages())
<div class="dataTables_paginate paging_full_numbers" id="more_pagination">
	<ul class="pagination">
	   {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
		   <li class="paginate_button first" id="data_filter_first"><a href="javascript:;" class="paginate_button first disabled readonly" aria-controls="data_filter" data-dt-idx="0" tabindex="0">First</a></li>
		   <li class="paginate_button previous" id="data_filter_previous"><a class="paginate_button previous disabled" aria-controls="data_filter" data-dt-idx="1" tabindex="0">Previous</a></li>
	   @else
	   		<li class="paginate_button first" id="data_filter_first"><a  href="javascript:;" onclick="paginate('{{ Request::url() }}?page=1',this)" class="paginate_button" aria-controls="data_filter" data-dt-idx="0" tabindex="0">First</a></li>
		   <li class="paginate_button previous" id="data_filter_previous"><a href="javascript:;" onclick="paginate('{{ $paginator->previousPageUrl() }}',this)" class="paginate_button previous" aria-controls="data_filter" data-dt-idx="1" tabindex="0">Previous</a></li>
	   @endif

	    {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))

            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <li class="paginate_button "><a class="paginate_button current paginate_active" aria-controls="data_filter" data-dt-idx="{{ $page }}" tabindex="0">{{ $page }}</a></li>
                    @else
                    <li class="paginate_button "><a href="javascript:;" onclick="paginate('{{$url}}',this)" class="paginate_button" aria-controls="data_filter" data-dt-idx="3" tabindex="0">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
	  {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        	<li class="paginate_button next" id="data_filter_next"> <a href="javascript:;" onclick="paginate('{{$paginator->nextPageUrl()}}',this)" class="paginate_button next" aria-controls="data_filter" data-dt-idx="9" tabindex="0">Next</a></li>
	   		<li class="paginate_button last" id="data_filter_last"><a href="javascript:;" onclick="paginate('{{ Request::url() }}?page={{$paginator->lastPage()}}',this)"  class="paginate_button last" aria-controls="data_filter" data-dt-idx="10" tabindex="0">Last</a></li>
        @else
        	<li class="paginate_button next" id="data_filter_next"> <a href="javascript:;" class="paginate_button next" aria-controls="data_filter" data-dt-idx="9" tabindex="0">Next</a></li>
	   		<li class="paginate_button last" id="data_filter_last"><a href="javascript:;"   class="paginate_button last" aria-controls="data_filter" data-dt-idx="10" tabindex="0">Last</a></li>
        @endif
	</ul>
</div>
@endif
<script type="text/javascript">
function paginate(url='',data){ 
	$("#more_pagination a").removeClass('paginate_active');
	$(data).addClass('paginate_active');
	if(url==''){
		var  url = "{{Request::url()}}";
	}
	var order_by ='';
	var from ='';
	var to ='';
	var name ='';
	if($("select[name='order_by'] option:selected").val())
	{
		order_by= $("select[name='order_by'] option:selected").val();  
	}     
	if($("input[name='from']").val())
	{
		from= $("input[name='from']").val();  
	}     
	if($("input[name='to']").val())
	{
		to= $("input[name='to']").val();  
	}
	if($("input[name='name']").val())
	{
		name= $("input[name='name']").val();  
	}
	var  _URL_ = url;
	var customURL = "&search="+name+"&status="+order_by+"&from="+from+"&to="+to;
	var _changeUrl = url+customURL;
	window.history.pushState("object or string", "Filter", _changeUrl);
	$.ajax({
		type: "get",
		url: _changeUrl,
		data: {},
		datatype: "html",
		beforeSend: function()
		{
		$('.ajaxloader').show();
		}
	}).done(function(data){ 
		$('.ajaxloader').hide();
		var appendid = 'result';
		if(data['appendid']){
			var appendid = data['appendid'];
		}
		$("#"+appendid).empty().append(JSON.parse(data['body']));
		//$("#custom_pagination_menu").empty().append(JSON.parse(data['paginMenu']));
		jQuery('#data_filter').dataTable({"paging": false,"bInfo":false});
	}).fail(function(jqXHR, ajaxOptions, thrownError){
		$('.ajaxloader').hide();
	});  
}
function serach(){
	var order_by ='';
	var from ='';
	var to ='';
	var name ='';
	var email ='';
	var proid ='';
	var userid ='';
	var loancat ='';
	if($("select[name='order_by'] option:selected").val())
	{
		order_by= $("select[name='order_by'] option:selected").val();  
	}     
	if($("input[name='from']").val())
	{
		from = $("input[name='from']").val();  
	}     
	if($("input[name='to']").val())
	{
		to = $("input[name='to']").val();  
	}
	if($("input[name='name']").val())
	{
		name= $("input[name='name']").val();  
	}	
	if($("input[name='email']").val())
	{
		email= $("input[name='email']").val();  
	}
	if($("input[name='proid']").val())
	{
		proid = $("input[name='proid']").val();  
	}
	if($("input[name='userid']").val())
	{
		userid = $("input[name='userid']").val();  
	}
	if($("select[name='loancat'] option:selected").val())
	{
		loancat= $("select[name='loancat'] option:selected").val();  
	} 
	var  url = "{{Request::url()}}";
	var  _URL_ = url;
	var customURL = "?search="+name;
	if(order_by!=''){
		customURL = customURL+"&status="+order_by;
	}
	if(from!=''){
		customURL = customURL+"&from="+from;
	}
	if(to!=''){
		customURL = customURL+"&to="+to;
	}
	if(email!=''){
		customURL = customURL+"&email="+email;
	}
	if(proid!=''){
		customURL = customURL+"&proid="+proid;
	}
	if(userid!=''){
		customURL = customURL+"&userid="+userid;
	}
	if(loancat!=''){
		customURL = customURL+"&loancat="+loancat;
	}

	var _changeUrl = url+customURL;
	window.history.pushState("object or string", "Filter", _changeUrl);
	$.ajax({
		type: "get",
		url: _changeUrl,
		data: {},
		datatype: "html",
		beforeSend: function()
		{
		$('.ajaxloader').show();
		}
	}).done(function(data){
		$('.ajaxloader').hide();
		if(data.length == 0){
			$('.ajaxloader').hide();
			return false;
		}
		$("#result").empty().append(JSON.parse(data['body']));
		jQuery('#data_filter').dataTable({"paging": false,"bInfo":false, "searching": false,"order": [[ 0, 'desc' ]]});
	}).fail(function(jqXHR, ajaxOptions, thrownError){
		$('.ajaxloader').hide();
	});	
}
function reset(){
	var order_by ='';
	var from ='';
	var to ='';
	var name ='';
	$("select[name='order_by']").val('');
	$("input[type='text']").val('');  
	var  url = "{{Request::url()}}";
	var  _URL_ = url;
	var customURL = "?search="+name;
	if(from!=''){
		customURL = customURL+"&from="+from;
	}
	if(to!=''){
		customURL = customURL+"&to="+to;
	}
	var _changeUrl = url+customURL;
	window.history.pushState("object or string", "Filter", _changeUrl);
	$.ajax({
		type: "get",
		url: _changeUrl,
		data: {},
		datatype: "html",
		beforeSend: function()
		{
		$('.ajaxloader').show();
		}
	}).done(function(data){
		$('.ajaxloader').hide();
		if(data.length == 0){
			$('.ajaxloader').hide();
			return false;
		}
		$("#result").empty().append(JSON.parse(data['body']));
		jQuery('#data_filter').dataTable({"paging": false,"bInfo":false,"searching": false,"order": [ 0, 'desc' ]});
	}).fail(function(jqXHR, ajaxOptions, thrownError){
		$('.ajaxloader').hide();
	});	
}
</script>