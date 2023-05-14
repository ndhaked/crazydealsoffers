<div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">Select Page <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
          @if(isset($advertisements))
            {!! Form::select('page', config::get('custom.advertisment_page_options'), null, ['class' => 'form-control', 'placeholder'=>'Select Page','title'=>'Please select Page','disabled','readonly']) !!}
          @else
            {!! Form::select('page', config::get('custom.advertisment_page_options'), null, ['required','class' => 'form-control', 'placeholder'=>'Select Page','title'=>'Please select Page']) !!}
          @endif
      </div>
   </div>
   @include('advertisements::upload_image')
   <div class="form-group">
      <label class="col-sm-2 control-label">Advertisement Link<span class="asterisk"></span></label>
      <div class="col-sm-8 ermsg">
          {{ Form::url('advertisement_link',null, ['class'=>'form-control','id'=>'advertisement_link','placeholder'=>'Advertisement link','title'=>'Please enter advertisement link','maxlength'=>'100','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
      </div>
   </div>
</div>

@section('uniquePageScript')

@endsection
