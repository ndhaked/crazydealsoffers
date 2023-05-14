@extends('admin.layouts.master')
@section('title', " ".trans('menu.sidebar.role_permission.assign_permission')." - " .app_name(). " :: Admin")
@section('content')
<section class="content-header">
  <h1><i class="fa fa-unlock"></i>
    {{trans('menu.sidebar.role_permission.main')}}
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
    <li><a href="{{route('roles.index')}}">{{trans('menu.sidebar.role.slug')}}</a></li>
    <li class="active">{{trans('menu.sidebar.role_permission.assign_permission')}}</li>
  </ol>
</section>
<section class="content">
  {!! Form::open(['route' => ['roles.premission.store', $role->slug],'class'=>'validate','id'=>'permissionForm']) !!}
    <div class="row">
        <div class="col-md-12">
           <div class="form-group">
              <label for="name">Role Name</label>
              <input class="form-control "  disabled="disabled" required  title="Please enter role name." value="{{ $role->display_name }}"  id="name" placeholder="Role Name" type="text">
           </div>
        </div>
     </div>
    <div class="row">
      @foreach($groupByPermission as $key => $lists)
        @if(count($lists) > 0)
          @php $checked =''; @endphp
            @if($role->Permissions->where('group_name',$key)->count() >0)
              @php $checked = 'checked="checked"'; @endphp
            @endif
            <div class="col-md-6">
              <div class="box box-success collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">
                  <input type="checkbox" id="{!! !empty($key)? str_replace(' ','_',$key) :'Extra' !!}" {!! $checked !!} onclick="checkBoxss('<?=!empty($key)? str_replace(' ','_',$key) :'Extra'?>')" />
                          <span for="{!! !empty($key)? str_replace(' ','_',$key) :'Extra' !!}">{!! !empty($key)?$key:'Extra' !!} &nbsp;</span></h3>
                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="{{count($lists)}} permission">{{count($lists)}}</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body">
                    <div class="row">
                      <div class="col-md-7"><b>Name</b></div>
                      <div class="col-md-5"><b>Action</b></div>
                    </div>
                    @php $i = 0; @endphp
                    @foreach($lists as $listName)
                       @php $checked =''; @endphp
                       @if($role->Permissions->where('id',$listName->id)->count() >0)
                          @php $checked = 'checked="checked"'; @endphp
                       @endif
                       <div class="row">
                          <div class="col-md-7">    {!! $listName->display_name !!}   </div>
                          <div class="col-md-5">
                            <div class="col-md-5 ckbox ckbox-primary">
                              <input type="checkbox" onclick="submitForm()" id="{{$listName->slug}}" {!! $checked !!} name="permission_id[]" value="{!! $listName->id !!}" class="{!! !empty($key)? str_replace(' ','_',$key) :'Extra' !!}" />
                              <label for="{{$listName->slug}}"></label>
                            </div>
                           </div>
                       </div>
                    @endforeach
                </div>
              </div>
            </div>
        @endif
      @endforeach
    </div>
  {!! Form::close() !!}
</section>
@endsection
@section('uniquePageScript')
<script type="text/javascript">
 function checkBoxss(ids)
 { 
      var lfckv = document.getElementById(ids).checked;        
      if(lfckv)
      {
          $('.'+ids).each(function() {
              this.checked = true;
          });
      }
      else
      {
          $('.'+ids).each(function() {
              this.checked = false;
          });
      }
      submitForm();
 }
 function submitForm()
 {
     var url = "{!! route('roles.premission.store', $role->slug) !!}"; 
      $.ajax({
         type: "POST",
         url: url,
         data: $("#permissionForm").serialize(), 
         success: function(data)
         {
            $(".lobibox-close").trigger('click');
            Lobibox.notify('success', {
                rounded: false,
                delay: 10000,
                delayIndicator: true,
                msg: "Permission succesfully Update"
            });
         }
      });
 }   
</script>
@endsection