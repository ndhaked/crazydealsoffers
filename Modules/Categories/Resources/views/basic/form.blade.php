<div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.name')}} <span class="asterisk">*</span></label>
      <div class="col-sm-8 ermsg">
          {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('menu.placeholder.name'),'title'=>'Please enter category name','maxlength'=>50]) }}
      </div>
   </div>
   @include('categories::upload_image')
</div>

@section('uniquePageScript')

@endsection
