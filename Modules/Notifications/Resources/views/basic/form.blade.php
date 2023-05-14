 <div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">User Selection <span class="asterisk"></span></label>
      <div class="col-sm-10 ermsg">
         {{ Form::radio('selection', 'all' , true) }} All Users &nbsp;&nbsp;
         {{ Form::radio('selection', 'manual' , false) }} Specific User
      </div>
   </div>
   <div class="form-group" id="users-input-div" style="display:none;">
      <label class="col-sm-2 control-label">Select Users (Multiple) <span class="asterisk">*</span></label>
      <div class="col-sm-10 ermsg">
         <input type="text" class="form-control" id="users-input" name="userId" placeholder="Search Users" title="Please select atleast one user"  />
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans('notifications::menu.sidebar.form.title')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ermsg">
         {{ Form::text('title',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Title','title'=>'Please enter title.']) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">Deal <span class="asterisk"></span></label>
      <div class="col-sm-10 ermsg">
         {{ Form::text('deal',null, ['autocomplete'=>'off','class'=>'form-control','id'=>'SearchByName','placeholder'=>'Search Deal by Name']) }}
         {{ Form::hidden('product_id',null, ['id'=>'product_id']) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans('menu.sidebar.email.form.message')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ">
        <span class="ermsg"> 
        {{ Form::textarea("message", (isset($data)) ? $data->body : null, ['required','class' => 'form-control', 'id'=>'message','rows'=>'10', 'title'=>"Please enter message.",'placeholder'=>"Enter message"]) }}
        </span>
      </div>
   </div>
</div>
