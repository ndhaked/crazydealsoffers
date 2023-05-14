@extends('layouts.app')
@section('title',trans('menu.contactus')." ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="row">
          <div class="preloader-section" v-if="loading" v-cloak>
            <div class="preloader-holder">
                <div class="loader"></div>
            </div>
        </div>
            <div class="col-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h2>Contact me</h2>
                    </div>
                    <div class="card-body">
                        <contactform-component></contactform-component>
                    </div>
                </div>
            </div>
        </div>
@endsection
