<div class="panel-body"> 
    <div class="form-group">
      <label class="col-sm-3 control-label">{{trans($model.'::menu.sidebar.form.allocation_time')}} {{trans($model.'::menu.sidebar.form.in_minutes')}} <span class="asterisk">*</span></label>
        <div class="col-sm-9">
          <span class="err_allocation_time"> 
          {{ Form::text('allocation_time',null, ['placeholder'=>trans($model.'::menu.sidebar.form.allocation_time'),'required','class'=>'form-control numberonly','id'=>'allocation_time','title'=>'Please enter '.strtolower(trans($model.'::menu.sidebar.form.allocation_time')),'maxlength'=>8,'autocomplete'=>'off']) }}
          </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">{{trans($model.'::menu.sidebar.form.return_time')}} {{trans($model.'::menu.sidebar.form.in_minutes')}} <span class="asterisk">*</span></label>
        <div class="col-sm-9">
            {{ Form::text('return_time',null, ['required','class'=>'form-control numberonly','id'=>'return_time','placeholder'=>trans($model.'::menu.sidebar.form.return_time'),'title'=>'Please enter return  time.','maxlength'=>8,'autocomplete'=>'off']) }}
        </div>
    </div>
    @if(isset($data) && $data->buffer_type == 'restriction' || !isset($data))
    <div class="form-group">
      <label class="col-sm-3 control-label">{{trans($model.'::menu.sidebar.form.from')}} ({{trans($model.'::menu.sidebar.form.km')}}) <span class="asterisk">*</span></label>
        <div class="col-sm-9">
          <span class="err_from"> 
          {{ Form::text('from',null, ['placeholder'=>trans($model.'::menu.sidebar.form.from'),'required','class'=>'form-control numberonly','id'=>'from','title'=>'Please enter '.strtolower(trans($model.'::menu.sidebar.form.from')),'maxlength'=>8,'autocomplete'=>'off']) }}
          </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">{{trans($model.'::menu.sidebar.form.to')}} ({{trans($model.'::menu.sidebar.form.km')}}) <span class="asterisk">*</span></label>
        <div class="col-sm-9">
            {{ Form::text('to',null, ['required','class'=>'form-control numberonly','id'=>'to','placeholder'=>trans($model.'::menu.sidebar.form.to'),'title'=>'Please enter to.','maxlength'=>8,'autocomplete'=>'off']) }}
        </div>
    </div>
    @endif  
</div>  