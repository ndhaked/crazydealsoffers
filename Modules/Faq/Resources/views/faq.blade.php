@extends('layouts.app')
@section('title',ucfirst($pageInfo->name)." ".trans('menu.pipe')." " .app_name())
@section('content')
  <section class="bg_light sec_pd2 help_banner">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 offset-lg-1 col-md-12">
          <div class="searchblock">
            <div class="section_title text-center mB30">
              <h2>@lang('menu.how_we_can_help')</h2>
            </div>
            <div class="searchbar text-center">
              {!! Form::open(['route' => 'faq.help','method' => 'GET']) !!}
                <div class="form-item mR20">
                  <i class="zmdi zmdi-search"></i>
                   {{ Form::text('keywords',@$_GET['keywords'], ['class'=>'form-style form-control','autocomplete'=>'off','placeholder'=>trans('menu.search_by_category')]) }}
                </div>
                <button type="submit" class="btn btn_gold" title="@lang('menu.search')">
                    @lang('menu.search')
                </button>
                <span class="subtxt">@lang('menu.help-popular-keywords')</span>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="helploan_block pB80">
    <div class="rightabstract"></div>
    <div class="container"> 
      <div class="browseloan nav-tab" id="myTab">
        @if(count($faqcategory)>0)
          <ul class="row nav nav-tabs border-0" id="myTab" role="tablist">
              @foreach($faqcategory as $kcat => $list)
                 <li class="col">
                    <div class="iconblock text-center @if($kcat==0) active @endif" data-toggle="tab" href="#{{$list->slug}}-tab" role="tab" aria-controls="{{$list->slug}}-tab" aria-selected="@if($kcat==0) true @else false @endif">
                      <div class="img_wrap"><img src="{{$list->PicturePath}}" alt="img"></div>
                      <p class="mT30">{!! $list->name !!}</p>
                    </div>
                  </li>
              @endforeach
          </ul>
          <div class="helptab pT70 accordion" id="accordionExample">
              <div class="tab-content" id="myTabContent">
                @foreach($faqcategory as $kcat => $list)
                <div class="tab-pane @if($kcat==0) show active @endif" id="{{$list->slug}}-tab" role="tabpanel" aria-labelledby="{{$list->slug}}-tab">
                  <div class="accordion" id="accordionExample">
                      @if($list->faqs)
                          @foreach($list->faqs as $k => $list)
                            <div class="block">
                              <h5 class="mb-0">
                                <button class="btn btn-link @if($k==0) active @endif" type="button" data-toggle="collapse" data-target="#collapse{{$kcat}}{{$k}}" aria-expanded="true" aria-controls="collapse{{$kcat}}{{$k}}">
                                  {{$k+1}}. {!! $list->question !!}
                                </button>
                              </h5>
                              <div id="collapse{{$kcat}}{{$k}}" class="collapse @if($k==0) show @endif" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="content">
                                  <p class="subtxt fz16">{!! $list->answer !!}</p>
                                </div>
                              </div>
                            </div>
                          @endforeach
                      @endif
                  </div>
                </div>
                @endforeach
              </div>
          </div> 
        @else
          <p class="text-center" style="width: 100%;"> No category available</p>
        @endif
      </div>
    </div>
  </section>
@endsection
