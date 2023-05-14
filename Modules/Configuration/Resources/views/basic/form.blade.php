<div class="form-group">
   <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.config_title')}} <span class="asterisk">*</span></label>
   <div class="col-sm-10 ermsg">
       {{ Form::text('config_title',null, ['required','class'=>'form-control','id'=>'config_title','placeholder'=>trans('menu.placeholder.title'),'title'=>trans('menu.validiation.please_enter_config_title'),'maxlength'=>150]) }}
   </div>
</div>
<div class="form-group">
   <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.config_value')}} <span class="asterisk">*</span></label>
   <div class="col-sm-10 ermsg">
      {{ Form::textarea('config_value',null, ['required','class'=>' form-control','id'=>'config_value','placeholder'=>trans('menu.placeholder.value'),'title'=>trans('menu.validiation.please_enter_config_value')]) }}
   </div>
</div>
