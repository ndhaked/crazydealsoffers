@extends('layouts.api_layout')
@section('title', ucwords($pages->name)."-".app_name())
@section('content')
<div class="contentpanel" style="text-align: center;">
   <div class="row">
    <h1>{{ucwords($pages->name)}}</h1>
    <p>{!! ucfirst($pages->description) !!}</p>
	 </div>
</div>
@endsection


