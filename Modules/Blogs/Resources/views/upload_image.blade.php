<div class="col-sm-6">
      <div class="form-group ermsg">
         <label class=" control-label">Blog Image 1 <span class="asterisk">*</span></label>
         <input type="file" name="profile_pic" id="mediaId" accept="image/*" @if(isset($data)) value="{{$blogs->S3Url}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('blog.uploadMedia')}}">
                <div class="input-group ">
                    <input type="text" value="@if(isset($blogs->image_1)) {{$blogs->image_1}} @endif" readonly="" id="logo-duplicate_mediaId" aria-describedby="basic-addon2" class="form-control">
                    <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($blogs) && $blogs->S3Url))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                    <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
                </div>
                {{ Form::hidden('image_1',null, ['required', 'id'=>'f_mediaId','title'=>'Please upload blog image 1']) }}
          <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
            <br>
            For best resolution dimension 1125x1080 px
          </small></div>
      </div>
  </div>
  <div class="col-sm-6">
      <div class="form-group ermsg">
         <label class=" control-label">Blog Image 2 @if(isset($blogs) && $blogs->image_2)&nbsp;&nbsp;<a style="text-align:left;" href="{{ route('blog.image.remove',[$blogs->id]) }}"><i class="fa fa-remove"> Remove</i></a>@endif <span class="asterisk"></span></label>
         <input type="file" name="profile_pic" id="mediaId2" accept="image/*" @if(isset($data)) value="{{$blogs->S3UrlImage2}}" @endif onchange="after_logo_select_2(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('blog.uploadMedia')}}">
                <div class="input-group ">
                    <input type="text" value="@if(isset($blogs->image_2)) {{$blogs->image_2}} @endif" readonly="" id="2logo-duplicate_mediaId2" aria-describedby="basic-addon2" class="form-control">
                    <span id="toggle_popover_mediaId2" rel="popover" class="input-group-addon btn @if(!(isset($blogs) && $blogs->S3UrlImage2))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                    <span onclick="document.getElementById('mediaId2').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
                </div>
                {{ Form::hidden('image_2',null, ['id'=>'f_mediaId2','title'=>'Please upload blog image 2']) }}
          <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
            <br>
            For best resolution dimension 1125x1080 px
          </small></div>
      </div>
  </div>
<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId" style="display:none">
  <div id="logo_popover_content">
      @if(isset($blogs->image_1))
      <img src="{{ $blogs->S3Url }}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId2" style="display:none">
  <div id="logo_popover_content2">
      @if(isset($blogs->image_2))
      <img src="{{ $blogs->S3UrlImage2 }}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
   jQuery(document).ready(function(){
      CKEDITOR.replace('ckeditor');
   });
    $('form').on('reset', function(e) {
        CKEDITOR.instances.ckeditor.setData(" ");
    });
</script>

<script type="text/javascript">
   function after_logo_select(id) {
        var uploadedFile = jQuery('#'+id)[0].files[0];
        jQuery('#logo-duplicate_'+id).val(uploadedFile.name);
        jQuery('#logo_popover_'+id+ ' #logo_popover_content').html('<img class="img-thumbnail tool-img" alt="" width="192" height="236" id="logo_popover_img_'+id+'" >');
        document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
        jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
    };
    jQuery(document).ready(function(){
      jQuery('#toggle_popover_mediaId').popover({
            html:true,
            title: 'Blog Image 1',
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
  function after_logo_select_2(id) {
        var uploadedFile = jQuery('#'+id)[0].files[0];
        jQuery('#2logo-duplicate_'+id).val(uploadedFile.name);
        jQuery('#logo_popover_'+id+ ' #logo_popover_content2').html('<img class="img-thumbnail tool-img" alt="" width="192" height="236" id="logo_popover_img_'+id+'" >');
        document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
        jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
    };
   jQuery(document).ready(function(){
      jQuery('#toggle_popover_mediaId2').popover({
            html:true,
            title: 'Blog Image 2',
            container: 'body',
            placement: 'top',
            trigger: 'click',
            content: function(){
                return $('#logo_popover_mediaId2').html();
            }
        }).click(function(){
            jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
        });  
  });  
</script>
@endsection

