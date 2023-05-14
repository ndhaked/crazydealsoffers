<div class="row">
    <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Title <span class="asterisk">*</span></label>
                 {{ Form::text('title',null, ['required','class'=>'form-control','id'=>'title','placeholder'=>'Title of the page','title'=>'Please enter title','maxlength'=>'250','data-msg-maxlength'=>trans('menu.validiation.title_may_not_be_greater')]) }}
        </div>
    </div>
    <!-- <div class="col-sm-6">
        <div class="form-group ermsg">
            <label class=" control-label">Video Link (Youtube only) <span class="asterisk"></span></label>
                 {{ Form::url('video_link',null, ['class'=>'form-control','id'=>'video_link','placeholder'=>'Video link','title'=>'Please enter item purchase link','maxlength'=>'250','data-msg-maxlength'=>trans('menu.validiation.video_may_not_be_greater')]) }}
        </div>
    </div> -->
</div>
<div class="row">
  @include('advertiseaffiliated::upload_image')
</div>
<div class="row">
   <div class="col-sm-6">
      <div class="form-group ermsg">
         <label class=" control-label">Banner Image Description</label>
               {{ Form::textarea("banner_description", null, ['class' => 'form-control', 'id'=>'ckeditor1','rows'=>'8', 'title'=>"Please enter banner image description.",'placeholder'=>"description",'style'=>'resize:none;']) }}
      </div>
   </div>
   <div class="col-sm-6">
      <div class="form-group ermsg">
         <label class=" control-label">Left Image Description </label>
               {{ Form::textarea("description_1", null, ['class' => 'form-control', 'id'=>'ckeditor2','rows'=>'8', 'title'=>"Please enter left image description.",'placeholder'=>"description",'style'=>'resize:none;']) }}
      </div>
   </div>
   <div class="col-sm-6">
      <div class="form-group ermsg">
            <label class=" control-label">Right Image Description </label>
               {{ Form::textarea("description_2", null, ['class' => 'form-control', 'id'=>'ckeditor3','rows'=>'8', 'title'=>"Please enter right image description.",'placeholder'=>"description",'style'=>'resize:none;']) }}
      </div>
   </div>
   <div class="col-sm-6">
      <div class="form-group ermsg">
            <label class=" control-label">Description</label>
               {{ Form::textarea("description", null, ['class' => 'form-control', 'id'=>'ckeditor4','rows'=>'8', 'title'=>"Please enter description.",'placeholder'=>"description",'style'=>'resize:none;']) }}
      </div>
   </div>
</div>