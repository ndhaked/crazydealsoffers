<!---------------Banner image---------------------->
<div class="col-sm-4">
    <div class="form-group ermsg">
        <label class=" control-label">Banner Image <span class="asterisk">*</span></label>
        <input type="file" name="banner_image" id="mediaId3" accept="image/*" @if(isset($data)) value="{{$advertiseaffiliated->S3Url}}" @endif onchange="after_logo_select_3(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('advertiseaffiliated.uploadMedia')}}">
            <div class="input-group ">
                <input type="text" value="@if(isset($advertiseaffiliated->banner_image)) {{$advertiseaffiliated->banner_image}} @endif" readonly="" id="3logo-duplicate_mediaId3" aria-describedby="basic-addon2" class="form-control">
                <span id="toggle_popover_mediaId3" rel="popover" class="input-group-addon btn @if(!(isset($advertiseaffiliated) && $advertiseaffiliated->S3Url))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('mediaId3').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
            {{ Form::hidden('banner_image',null, ['required', 'id'=>'f_mediaId3','title'=>'Please upload image 1']) }}
        <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
        <br>
        For best resolution dimension 1125x1080 px
        </small></div>
    </div>
</div>

<!--------------left side image-------------------------->
<div class="col-sm-4">
    <div class="form-group ermsg">
        <label class=" control-label">Left Image </label>
        <input type="file" name="image_1" id="mediaId" accept="image/*" @if(isset($data)) value="{{$advertiseaffiliated->S3Url}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('advertiseaffiliated.uploadMedia')}}">
            <div class="input-group ">
                <input type="text" value="@if(isset($advertiseaffiliated->image_1)) {{$advertiseaffiliated->image_1}} @endif" readonly="" id="logo-duplicate_mediaId" aria-describedby="basic-addon2" class="form-control">
                <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($advertiseaffiliated) && $advertiseaffiliated->S3Url))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
            {{ Form::hidden('image_1',null, ['id'=>'f_mediaId','title'=>'Please upload image 1']) }}
        <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
        <br>
        For best resolution dimension 1125x1080 px
        </small></div>
    </div>
</div>

<!--------------Right side image-------------------------->
<div class="col-sm-4">
    <div class="form-group ermsg">
        <label class=" control-label">Right Image  <span class="asterisk"></span></label>
        <input type="file" name="image_2" id="mediaId2" accept="image/*" @if(isset($data)) value="{{$advertiseaffiliated->S3UrlImage2}}" @endif onchange="after_logo_select_2(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('advertiseaffiliated.uploadMedia')}}">
            <div class="input-group ">
                <input type="text" value="@if(isset($advertiseaffiliated->image_2)) {{$advertiseaffiliated->image_2}} @endif" readonly="" id="2logo-duplicate_mediaId2" aria-describedby="basic-addon2" class="form-control">
                <span id="toggle_popover_mediaId2" rel="popover" class="input-group-addon btn @if(!(isset($advertiseaffiliated) && $advertiseaffiliated->S3UrlImage2))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('mediaId2').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
            {{ Form::hidden('image_2',null, ['id'=>'f_mediaId2','title'=>'Please upload right image']) }}
        <div class="description"><small>Max 5Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
        <br>
        For best resolution dimension 1125x1080 px
        </small></div>
    </div>
</div>

<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId3" style="display:none">
  <div id="logo_popover_content3">
      @if(isset($advertiseaffiliated->banner_image))
      <img src="{{ $advertiseaffiliated->S3Url }}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId" style="display:none">
  <div id="logo_popover_content">
      @if(isset($advertiseaffiliated->image_1))
      <img src="{{ $advertiseaffiliated->S3UrlImage2 }}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId2" style="display:none">
  <div id="logo_popover_content2">
      @if(isset($advertiseaffiliated->image_2))
      <img src="{{ $advertiseaffiliated->S3UrlImage3 }}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
   jQuery(document).ready(function(){
      CKEDITOR.replace('ckeditor1');
      CKEDITOR.replace('ckeditor2');
      CKEDITOR.replace('ckeditor3');
      CKEDITOR.replace('ckeditor4');
   });
    $('form').on('reset', function(e) {
        CKEDITOR.instances.ckeditor.setData(" ");
    });
</script>

<script type="text/javascript">
    function after_logo_select_3(id) {
        var uploadedFile = jQuery('#'+id)[0].files[0];
        jQuery('#3logo-duplicate_'+id).val(uploadedFile.name);
        jQuery('#logo_popover_'+id+ ' #logo_popover_content3').html('<img class="img-thumbnail tool-img" alt="" width="192" height="236" id="logo_popover_img_'+id+'" >');
        document.getElementById('logo_popover_img_'+id).src = URL.createObjectURL(uploadedFile);
        jQuery('#logo_popover_'+id).removeClass('disabled disabled_advanced');
    };
    jQuery(document).ready(function(){
      jQuery('#toggle_popover_mediaId3').popover({
            html:true,
            title: 'Left Image',
            container: 'body',
            placement: 'top',
            trigger: 'click',
            content: function(){
                return $('#logo_popover_mediaId3').html();
            }
        }).click(function(){
            jQuery(this).children('i').toggleClass('fa-eye fa-eye-slash');
        });  
    }); 
    
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
            title: 'Left Image',
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
            title: 'Right Image',
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

