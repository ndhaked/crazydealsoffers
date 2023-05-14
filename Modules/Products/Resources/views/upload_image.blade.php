 <div class="col-sm-6">
  <div class="form-group">
     <label class=" control-label">Product Image <span class="asterisk">*</span></label>
     <input type="file" name="profile_pic" id="mediaId" accept="image/*" @if(isset($data)) value="{{$products->S3Url}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('product.uploadMedia')}}">
            <div class="input-group">
                <input type="text" value="@if(isset($products->image)) {{$products->image}} @endif" readonly="" id="logo-duplicate_mediaId" aria-describedby="basic-addon2" class="form-control">
                <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($products) && $products->S3Url))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
                <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
            </div>
            <div class="ermsg">
               {{ Form::hidden('image',null, ['required','id'=>'f_mediaId','title'=>'Please upload product image']) }}
            </div>
           
      <div class="description"><small>Max 2Mb image size allowed. Allowed types : jpeg, png, jpg, gif.
         <br>
            For best resolution dimension 780x540 px
      </small></div>
  </div>
</div>

<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId" style="display:none">
  <div id="logo_popover_content">
      @if(isset($products->S3Url))
      <img src="{{ $products->S3Url }}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
@section('uniquePageScript')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
<script>
   jQuery(document).ready(function(){
      CKEDITOR.replace('description');
      CKEDITOR.replace('pinterest_description');
      CKEDITOR.replace('instagram_description');
      <?php foreach($groupsList as $group){
       /* if(in_array($group["id"],explode(',',env('FACEBOOK_GROUP_IDS')))){ */ ?>
            CKEDITOR.replace('facebook_description_<?php echo $group["id"] ?>');
      <?php /*} */
	  } ?>
      <?php 
        if(env('ENABLE_FACEBOOK_PAGES_POST')==true){
        foreach($pagesInfo as $key=>$page){ ?>
            CKEDITOR.replace('facebook_description_business_<?php echo $key ?>');
        <?php  } } 
      ?>
   });
    $('form').on('reset', function(e) {
        CKEDITOR.instances.description.setData(" ");
        CKEDITOR.instances.facebook_description_1.setData(" ");
        CKEDITOR.instances.facebook_description_2.setData(" ");
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
            title: 'Product Image',
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
   // Date Picker
  $('#expiry_date').datetimepicker({
     dateFormat: 'yy-mm-dd'
  });
 
    $(function () {
        $('#delete_status').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
    
    $(function () {
        $('.social_checkbox').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        $('.social_checkbox_instagram').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        $('.social_checkbox_pinterest').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        $('.social_checkbox_business').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        //facebook groups
        $('.social_checkbox').on('ifUnchecked',function(event){ 
            
                  let div_id=event.target.id.match(/\d+/); 
                  let checked_box_type=event.target.id; //alert(checked_box_type);
                  if(checked_box_type.indexOf('same_description')>-1){  
                    $('#facebook_desc_'+div_id).show(); 
                  }
                 
                  
                  if(checked_box_type.indexOf('facebook_')>-1){ 
                    $('#same_as_'+div_id).hide();
                    $('#facebook_desc_'+div_id).hide(); 
                  }
        });
        $('.social_checkbox').on('ifChecked',function(event){
                  let div_id=event.target.id.match(/\d+/); 
                  let checked_box_type=event.target.id; //alert(checked_box_type);
                  if(checked_box_type.indexOf('same_description')>-1){  
                    $('#facebook_desc_'+div_id).hide(); 
                  }
                 
                 
                  if(checked_box_type.indexOf('facebook_')>-1){ 
                    $('#same_as_'+div_id).show();
                    if($('#same_description'+div_id).is(":checked")){
                        $('#facebook_desc_'+div_id).hide(); 
                    }else{
                        $('#facebook_desc_'+div_id).show(); 
                    }
                  }
        });
         //facebook business page
         $('.social_checkbox_business').on('ifUnchecked',function(event){ 
            
            let div_id=event.target.id.match(/\d+/); 
            let checked_box_type=event.target.id; //alert(checked_box_type);
            if(checked_box_type.indexOf('same_description_business_')>-1){  
              $('#facebook_desc_business_'+div_id).show(); 
            }
           
            
            if(checked_box_type.indexOf('facebook_business_')>-1){ 
              $('#same_as_business_'+div_id).hide();
              $('#facebook_desc_business_'+div_id).hide(); 
            }
  });
  $('.social_checkbox_business').on('ifChecked',function(event){
            let div_id=event.target.id.match(/\d+/); 
            let checked_box_type=event.target.id; //alert(checked_box_type);
            if(checked_box_type.indexOf('same_description')>-1){  
              $('#facebook_desc_business_'+div_id).hide(); 
            }
           
           
            if(checked_box_type.indexOf('facebook_business_')>-1){ 
              $('#same_as_business_'+div_id).show();
              if($('#same_description_business_'+div_id).is(":checked")){
                  $('#facebook_desc_business_'+div_id).hide(); 
              }else{
                  $('#facebook_desc_business_'+div_id).show(); 
              }
            }
  });
//instagram
        $('.social_checkbox_instagram').on('ifUnchecked',function(event){ 
            
            let div_id=event.target.id.match(/\d+/); 
            let checked_box_type=event.target.id; //alert(checked_box_type);
            if(checked_box_type.indexOf('same_description')>-1){  
              $('#instagram_desc').show(); 
            }
           
            
            if(checked_box_type=='instagram'){ 
              $('#same_as_instagram').hide();
              $('#instagram_desc').hide(); 
            }
  });
  $('.social_checkbox_instagram').on('ifChecked',function(event){
            let div_id=event.target.id.match(/\d+/); 
            let checked_box_type=event.target.id; //alert(checked_box_type);
            if(checked_box_type.indexOf('same_description')>-1){  
              $('#instagram_desc').hide(); 
            }
           
           
            if(checked_box_type=='instagram'){ 
              $('#same_as_instagram').show();
              if($('#same_description_instagram').is(":checked")){
                  $('#instagram_desc').hide(); 
              }else{
                  $('#instagram_desc').show(); 
              }
            }
  });

  //pinterest
  $('.social_checkbox_pinterest').on('ifUnchecked',function(event){ 
            
            let div_id=event.target.id.match(/\d+/); 
            let checked_box_type=event.target.id; //alert(checked_box_type);
            if(checked_box_type.indexOf('same_description')>-1){  
              $('#pinterest_desc').show(); 
            }
           
            
            if(checked_box_type=='pinterest'){ 
              $('#same_as_pinterest').hide();
              $('#pinterest_desc').hide(); 
            }
  });
  $('.social_checkbox_pinterest').on('ifChecked',function(event){
            let div_id=event.target.id.match(/\d+/); 
            let checked_box_type=event.target.id; //alert(checked_box_type);
            if(checked_box_type.indexOf('same_description')>-1){  
              $('#pinterest_desc').hide(); 
            }
           
           
            if(checked_box_type=='pinterest'){ 
              $('#same_as_pinterest').show();
              if($('#same_description_pinterest').is(":checked")){
                  $('#pinterest_desc').hide(); 
              }else{
                  $('#pinterest_desc').show(); 
              }
            }
  });
    });

</script>
@endsection
