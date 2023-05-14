@extends('layouts.app')
@section('content')
<main class="main-wrapper">
    <div class="static-content">
        <div class="container">
            <div class="row">
            <div class="col-sm-12">
                <div class="title-main">
                <h3>
                    FAQs              
                </h3>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="static-sec">
                        <div class="accordion" id="accordionExample">

                            
                            @foreach($faq as $key=>$ques)
                                <div class="card">
                                    <div class="card-header" id="heading{{$key}}">
                                        <h5 class="mb-0">
                                        <button class="collapsed" type="button" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="{{($key==0)?'true':'false'}}" aria-controls="collapse{{$key}}">
                                            {{ $ques->question }}
                                            <i class="accordian-drop">
                                            </i>
                                        </button>
                                        </h5>
                                    </div>
                                    <div id="collapse{{$key}}" class="collapse {{($key==0)?'show':''}}" aria-labelledby="heading{{$key}}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            {{ $ques->answer }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection