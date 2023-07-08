@if(count($categories)>0)
<section class="category-area">
	<div class="container">
		<div class="title-main">
          <h3>Our Popular Category</h3>
        </div>
		<div class="category-list-area">
            @foreach($categories as $list)
			<div class="category-box">
				<a href="{{route('category.products',$list->slug)}}" alt="{{ucwords($list->name)}}" title="{{ucwords($list->name)}}">
					<div class="categoryBox-img">
						<img src="{{ $list->picture_path }}" alt="{{ucwords($list->name)}}" title="{{ucwords($list->name)}}">
					</div>
					<p>{{ucwords($list->name)}}</p>
				</a>
			</div>
            @endforeach
	   </div>
    </div>
</section>
@endif