<div class="col-sm-6">
  <div class="form-group">
     <label class=" control-label">{{trans('menu.sidebar.users.form.image')}} </label>
      <input type="file" name="profile_pic" id="mediaId" accept="image/*" @if(isset($user)) value="{{$user->picture_path}}" @endif onchange="after_logo_select(this.id)"  style="display:none" class="form-control onlyimageupload" data-uploadurl="{{route('users.uploadProfile')}}">
      <div class="input-group">
          <input type="text" value="@if(isset($user->image)) {{$user->image}} @endif" readonly="" id="logo-duplicate_mediaId" aria-describedby="basic-addon2" class="form-control">
          <span id="toggle_popover_mediaId" rel="popover" class="input-group-addon btn @if(!(isset($user) && $user->picture_path))  disabled_advanced @endif" ><i class="fa fa-eye"></i></span>
          <span onclick="document.getElementById('mediaId').click()" id="basic-addon2" class="input-group-addon btn"><i class="fa fa-plus"></i></span>
      </div>
      <div class="description"><small>Max 2Mb image size allowed. Allowed types : jpeg, png, jpg, gif.</small></div>
  </div>
   {{ Form::hidden('image',null, ['id'=>'f_mediaId']) }}
</div>
<!-- Html use for Gift Images logo -->
<div id="logo_popover_mediaId" style="display:none">
  <div id="logo_popover_content">
      @if(isset($user->image))
      <img src="{{$user->ThumbPicturePath}}" class="img-thumbnail tool-img" alt="" width="304" height="192" id="logo_popover_img_mediaId" >
      @else
      <p id="logo_popover_placeholder">No Media has been selected yet</p>
      @endif
  </div>
</div>
@section('uniquePageScript')
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
            title: 'Profile Picture',
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
</script>
@endsection

