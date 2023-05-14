@extends('layouts.app')
@section('title', " ".$pageInfo->name." ".trans('menu.pipe')." " .app_name())
@section('content')
<section class="containerbanner mT50">
   <div class="container animated6 fadeInLeft">
      <div class="section_title text-center mB30">
         <h2>{!! $pageInfo->name !!}</h2>
      </div>
      <div class="bannercontent">
         <div class="imgblock"><img src="{{$pageInfo->BannerPath}}" alt="image"></div>
      </div>
   </div>
</section>
<section class="loanbanner sec_pd2 decimal_list">
     <div class="container animated6 fadeInLeft">
        <div class="content">
          	<p>{!! $pageInfo->Description !!}</p>
        </div>
     </div>
</section>
@endsection