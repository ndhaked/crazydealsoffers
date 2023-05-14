<div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">Select Page <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
          @if(isset($slider))
            <!-- {!! Form::select('slider_order', [1=>1,2=>2,3=>3,4=>4,5=>5], null, ['class' => 'form-control', 'placeholder'=>'Select Page','title'=>'Please select Page','disabled','readonly']) !!} -->

            {!! Form::select('slider_order', $sliderOrder, null, ['required','class' => 'form-control', 'placeholder'=>'Select Page','title'=>'Please select Page']) !!}
          @else
            {!! Form::select('slider_order', $sliderOrder, null, ['required','class' => 'form-control', 'placeholder'=>'Select Page','title'=>'Please select Page']) !!}
          @endif
      </div>
   </div>

   <div class="form-group">
      <label class="col-sm-2 control-label">Title <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
         {{ Form::text('title',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Title of the page','title'=>'Please enter title','maxlength'=>'250','data-msg-maxlength'=>trans('menu.validiation.title_may_not_be_greater')]) }}
      </div>
   </div>

   @include('slider::upload_image')
   <div class="form-group">
      <label class="col-sm-2 control-label">Banner Link<span class="asterisk"></span></label>
      <div class="col-sm-8 ermsg">
          {{ Form::url('url',null, ['class'=>'form-control','id'=>'url','placeholder'=>'Banner link','title'=>'Please enter Banner link','maxlength'=>'100','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
      </div>
   </div>
</div>

@section('uniquePageScript')

@endsection
