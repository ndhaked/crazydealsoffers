{!! Form::open(['method'=>'post', 'route' => ['users.storeChangeUserPassword'],'class'=>'form-horizontal account-form','id'=>'F_changePassword','autocomplete'=>'off']) !!}
  <div class="form-group">
  <label for="npass" class="col-sm-3 control-label">{{trans('menu.sidebar.users.form.new_password')}} <span class="asterisk">*</span></label>
  {{ Form::hidden('slug', $user->slug ,['id'=>'slug']) }}
  <div class="col-sm-9 ermsg">
   {!!Form::password('password', ['required','placeholder'=>trans('menu.placeholder.new_password'),'id'=>'npass', 'class' => 'form-control', 'autocomplete'=>'off' ])!!}
  </div>
  </div>
  <div class="form-group">
  <label for="password_confirmation" class="col-sm-3 control-label">{{trans('menu.sidebar.users.form.confirm_password')}}<span class="asterisk">*</span></label>
  <div class="col-sm-9 ermsg">
    {!!Form::password('password_confirmation',  ['required','placeholder'=>trans('menu.placeholder.confirm_password'), 'class' => 'form-control', 'id'=>'password_confirmation','autocomplete'=>'off'])!!}
  </div>
  </div>
  <div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" id="changePassword" class="btn btn-primary directSubmit">@lang('users::menu.sidebar.form.save_changes')</button>
    <button type="reset" class="btn btn-danger  cancel-btn">Reset</button>
  </div>
  </div>
{!! Form::close() !!}