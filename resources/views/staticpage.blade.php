@extends('layouts.app')
@section('title',$data->MetaTitle)
@section('description',$data->MetaDescription)
@section('content')
<main class="main-wrapper">
    <div class="static-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title-main">
                    <h3>{{ $data->name_en }}</h3>
                    </div>
                </div>
            </div>
            @if($data->slug!='aboutus')
                <div class="row">
                    <div class="col-sm-12">
                        {!! $data->description_en !!}
                    </div>
                </div>
            @else
            <div class="image-static-data">
                    <div class="col-sm-12">
                        <img src="{{ $data['image'] }}" alt="">
                        </div>
                        <div class="col-sm-12">
                            <p class="margin-top-20">
                                {!! $data['description_en'] !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection