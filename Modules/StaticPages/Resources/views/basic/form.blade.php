 <div class="panel-body">
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.name')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ermsg">
          {{ Form::text('name_en',null, ['required','class'=>'form-control','id'=>'name_en','placeholder'=>trans('menu.placeholder.name'),'title'=>trans('menu.validiation.please_enter_name')]) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.meta_keyword')}}</label>
      <div class="col-sm-10 ermsg">
         {{ Form::text('meta_keyword_en',null, ['class'=>'form-control','id'=>'meta_keyword_en','placeholder'=>trans('menu.placeholder.meta_keyword'),'title'=>trans('menu.validiation.please_enter_meta_keyword')]) }}
      </div>
   </div>   
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.meta_description')}} </label>
      <div class="col-sm-10 ermsg">
         {{ Form::text('meta_description_en',null, ['class'=>'form-control','id'=>'meta_description_en','placeholder'=>trans('menu.placeholder.meta_desc'),'title'=>trans('menu.validiation.please_enter_meta_desc'),'rows'=>'5']) }}
      </div>
   </div>
   <div class="form-group">
      <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.description')}} <span class="asterisk">*</span></label>
      <div class="col-sm-10 ermsg">
         {{ Form::textarea("description_en", null, ['class' => 'form-control ckeditor', 'id'=>'description_en','rows'=>'10', 'title'=>trans('menu.validiation.please_enter_desc'),'placeholder'=>trans('menu.placeholder.enter_desc')]) }}
      </div>
   </div>
    @if($data->slug=='aboutus')
    <div class="form-group">
        <label class="col-sm-2 control-label">{{trans($model.'::menu.sidebar.form.image')}} <span class="asterisk"></span></label>
        <div class="col-sm-1" style="padding-top: 5px;width: 3%;">
            <span class="err_banner">
                {{ Form::checkbox('banner',1, NULL,['class'=>'minimal','id'=>'banner']) }}
            </span>
        </div>
        <div class="col-sm-7 ermsg" style="display: none;" id="banner_div">
              <input type="file" name="profile_pic" id="mediaId" accept="image/*" @if(isset($data)) value="{{$data->banner_path}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route($model.'.mediaStore')}}">
              <div class="input-group">
                  <input type="text" value="@if(isset($data->banner_image)) {{$data->banner_image}} @endif" readonly="" id="logo-duplicate_mediaId" aria-describedby="basic-addon2" class="form-control">
                  <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($data) && $data->banner_path))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                  <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
              </div>
              <div class="description"><small>Max 2Mb image size allowed. Allowed types : jpeg, png, jpg, gif.(Size 1146 X 538)</small></div>
               {{ Form::hidden('banner_image',null, ['id'=>'f_mediaId','title'=>'Please select image',]) }}
            </div>
    </div>
    @endif
</div>
<div id="logo_popover_mediaId" style="display:none">
    <div id="logo_popover_content">
      @if(isset($data->banner_image))
        <img src="{{$data->banner_path}}" class="img-thumbnail tool-img" alt="" width="304" height="236" id="logo_popover_img_mediaId" >
      @else
        <p id="logo_popover_placeholder">No media has been selected yet</p>
      @endif
    </div>
</div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script type="text/javascript">
    function after_logo_select(id) {
    var uploadedFile = jQuery('#'+id)[0].files[0];
    jQuery('#logo-duplicate_'+id).val(uploadedFile.name);
    jQuery('#logo_popover_'+id+ ' #logo_popover_content').html('<img class="img-thumbnail tool-img" alt="" width="304" height="236" id="logo_popover_img_'+id+'" >');
    document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
    jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
  };
  jQuery(document).ready(function () {
    jQuery('#toggle_popover_mediaId').popover({
        html:true,
        title: 'Banner Image',
        container: 'body',
        placement: 'top',
        trigger: 'click',
        content: function(){
            return $('#logo_popover_mediaId').html();
        }
    }).click(function(){
        jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
    });  
  });

   $(function () {
        if($('#banner'). prop("checked") == true){
            $("#banner_div").show();
            $("#f_mediaId").attr("required","required");
        }
        $('#banner').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        }).on('ifChanged', function(e) {
            var isChecked = e.currentTarget.checked;
            if (isChecked == true) {
                $("#banner_div").show();
                $("#f_mediaId").attr("required","required");
            }else {
                $("#banner_div").hide();
                $("#f_mediaId").attr("required",false);
            }
        });
    });
   $('form').on('reset', function(e) {
        CKEDITOR.instances.description_en.setData(" ");
    });
</script>
@endsection