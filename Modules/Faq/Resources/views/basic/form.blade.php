<div class="panel-body">
    <div class="form-group">
        <label class="col-sm-2 control-label">Question <span class="asterisk">*</span></label>
        <div class="col-sm-10 ">
            <span class="ermsg">
            {{ Form::text('question',null, ['required','class'=>'form-control','id'=>'question','placeholder'=>'Enter question','title'=>'Please enter question']) }}
            </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Answer<span class="asterisk">*</span></label>
        <div class="col-sm-10 ">
            <span class="ermsg"> 
                {{ Form::textarea("answer", null, ['required','class' => 'form-control', 'id'=>'answer','rows'=>'10', 'title'=>'Please enter answer','placeholder'=>'Enter answer']) }}
            </span>
        </div>
    </div>
</div>
