<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">{{trans('users::menu.sidebar.form.name')}} <span class="asterisk">*</span></label>
                 {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>trans('users::menu.sidebar.form.name'),'title'=>trans('menu.validiation.please_enter_name'),'maxlength'=>'50','data-msg-maxlength'=>trans('menu.validiation.name_may_not_be_greater')]) }}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class="control-label">{{trans('users::menu.sidebar.form.email')}} <span class="asterisk">*</span></label>
             {{ Form::email('email',null, ['required','class'=>'form-control','id'=>'email','placeholder'=>trans('users::menu.sidebar.form.email'),'title'=>'Please enter email']) }} 
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">{{trans('menu.sidebar.users.form.password')}} @if(!isset($user))<span class="asterisk">*</span>@endif</label>
                <input class="form-control"  name="password"  @if(!isset($user)) required @endif title="Please enter password." value="{{ old('password') }}"  id="password" placeholder="Password" type="password">
        </div>
    </div>
     <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">{{trans('menu.sidebar.users.form.confirm_password')}} @if(!isset($user))<span class="asterisk">*</span>@endif</label>
                <input class="form-control"  name="password_confirmation" @if(!isset($user)) required @endif title="Confirm password is wrong." value="{{ old('password_confirmation') }}"  id="password_confirmation" placeholder="Confirm Password" type="password" equalTo="#password" data-msg-required="Enter confirm password.">
        </div>
    </div>
</div>
<div class="row">
     @include('users::upload_image')
</div>
