<div class="custom-pagination">
	@if ($paginator->hasPages())
	    <nav aria-label="Page navigation example">
	        <ul class="pagination">
	            {{-- Previous Page Link --}}
	            @if ($paginator->onFirstPage())
	                <li class="page-link disabled">
	                    <a href="javascript:;"><img src="{{ asset('/front/images/icons/ic-arrow-left.svg') }}" alt=""></a>
	                </li>
	            @else
	                <li class="page-item">
	                    <a href="javascript:;" rel="prev" aria-label="@lang('pagination.previous')" class="page-link" onclick="paginate('{{$paginator->previousPageUrl()}}',this)"><img src="{{ asset('/front/images/icons/ic-arrow-left.svg') }}" alt=""></a>
	                </li>
	            @endif
	            {{-- Pagination Elements --}}
	            @foreach ($elements as $element)
	                {{-- "Three Dots" Separator --}}
	                @if (is_string($element))
	                    <li class="page-item disabled" aria-disabled="true"><span>{{ $element }}</span></li>
	                @endif

	                {{-- Array Of Links --}}
	                @if (is_array($element))
	                    @foreach ($element as $page => $url)
	                        @if ($page == $paginator->currentPage())
	                            <li class="page-item active" aria-current="page"><a class="page-link" href="javascript:;">{{ $page }}</a></li>
	                        @else
	                            <li class="page-item"><a href="javascript:;" class="page-link" onclick="paginate('{{$url}}',this)">{{ $page }}</a></li>
	                        @endif
	                    @endforeach
	                @endif
	            @endforeach
	            {{-- Next Page Link --}}
	            @if ($paginator->hasMorePages())
	                <li class="page-item">
	                    <a href="javascript:;" rel="next" aria-label="@lang('pagination.next')" class="page-link"  onclick="paginate('{{$paginator->nextPageUrl()}}',this)"><img src="{{ asset('/front/images/icons/ic-arrow-right.svg') }}" alt=""></a>
	                </li>
	            @else
	                <li class="page-link disabled">
	                    <a href="javascript:;"><img src="{{ asset('/front/images/icons/ic-arrow-right.svg') }}" alt=""></a>
	                </li>
	            @endif
	        </ul>
	    </nav>
	@endif
</div>
<script type="text/javascript">
function paginate(url='',data){ 
	if(url != 'platform_id'){
		$("#menutabs a").removeClass('active');
		$(data).addClass('active');
	}
	if(url==''){
		var  url = "{{Request::url()}}";
	}else if(url == 'platform_id'){
		var platform_id = $(data).val();
		var  url = "{{Request::url()}}"+'?platform='+platform_id;
	}
	var _changeUrl = url;
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
		if(data['fullHeading']){ 
			$("#fullHeading").html(data['fullHeading']);
		}
		if(data['metaDescription']){
			document.getElementsByTagName('meta')["description"].content = data['metaDescription'];
		}
		if(data['metaTitle']){ 
			document.title = data['metaTitle'];
		}
		if(data['apppendid']){
			window.history.pushState("object or string", "Filter", _changeUrl);
			$('#'+data['apppendid']).empty().append(JSON.parse(data['body']));
		}else{
			window.history.pushState("object or string", "Filter", _changeUrl);
			$('#result').empty().append(JSON.parse(data['body']));
		}
		
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
	var sort_by ='';
	if($("select[name='order_by'] option:selected").val())
	{
		order_by= $("select[name='order_by'] option:selected").val();  
	}  
	if($("select[name='sort_by'] option:selected").val())
	{
		sort_by= $("select[name='sort_by'] option:selected").val();  
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
	var  url = REQUEST_URL;
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
	if(sort_by!=''){
		customURL = customURL+"&sortby="+sort_by;
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
		//jQuery('#data_filter').dataTable({"paging": false,"bInfo":false, "searching": false});
	}).fail(function(jqXHR, ajaxOptions, thrownError){
		$('.ajaxloader').hide();
	});	
}
</script>
