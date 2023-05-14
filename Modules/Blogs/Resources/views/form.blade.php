<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Title <span class="asterisk">*</span></label>
                 {{ Form::text('title',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Title of the blog','title'=>'Please enter blog title','maxlength'=>'250','data-msg-maxlength'=>trans('menu.validiation.title_may_not_be_greater')]) }}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Video Link (Youtube only) <span class="asterisk"></span></label>
                 {{ Form::url('video_link',null, ['class'=>'form-control','id'=>'video_link','placeholder'=>'Video link','title'=>'Please enter item purchase link','maxlength'=>'250','data-msg-maxlength'=>trans('menu.validiation.video_may_not_be_greater')]) }}
        </div>
    </div>
</div>
<div class="row">
  @include('blogs::upload_image')
</div>
<div class="row">
  <div class="col-sm-12">
        <div class="form-group ermsg">
            <label class=" control-label">Description <span class="asterisk">*</span></label>
                 {{ Form::textarea("description", null, ['class' => 'form-control', 'id'=>'ckeditor','rows'=>'10', 'title'=>"Please enter description.",'placeholder'=>"description"]) }}
        </div>
    </div>
</div>