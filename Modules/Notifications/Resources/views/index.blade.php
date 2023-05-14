@extends('admin.layouts.master')
@section('title', " ".trans('notifications::menu.sidebar.manage')." - " .app_name(). " :: Admin")
@section('content')
    <link rel="stylesheet" href="{{URL::to('tokeninput/styles/token-input.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{URL::to('tokeninput/styles/token-input-facebook.css')}}" type="text/css" />
    <section class="content-header">
      <h1><i class="{{trans('notifications::menu.font_icon')}}"></i>
        {{trans('notifications::menu.sidebar.manage')}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
         <li class="active">{{trans('notifications::menu.sidebar.manage')}}</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-success">
          <div class="box-header with-border">
            <?php /*
            <br><br>
            {!! Form::open(['route' => 'notifications.index','method' => 'GET']) !!}
            <div class="row">
                <div class='col-md-2'>
                    <div class="form-group">
                          {{ Form::text('name',@$_GET['name'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.search_by_name')]) }}
                    </div>
                </div>
                <div class='col-md-2'>
                  <div class="form-group">
                      <div class='input-group'>
                          {{ Form::text('email',@$_GET['email'], ['class'=>'form-control','placeholder'=>trans('users::menu.sidebar.form.email')]) }}
                      </div>
                  </div>
                </div>
                <div class='col-md-2'>
                    <button type="submit" class="btn btn-success btn-flat pull-right btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-search"></i> {{ trans('users::menu.sidebar.form.search') }}
                    </button>
                </div>
                <div class='col-md-2'>
                    <a href="{{route('notifications.index')}}" class="btn btn-success btn-flat pull-left btn-edit-booking-save" title="@lang('users::menu.sidebar.form.search')">
                        <i class="fa fa-refresh"></i> {{ trans('users::menu.sidebar.form.clear_search') }}
                    </a>
                 </div>
            </div>
            {!! Form::close() !!}
            */?>
            </div>
         {!! Form::open(['route' => 'notifications.store','class'=>'form-horizontal','id'=>'F_sendNotification']) !!}
        <?php /*
        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <!-- <th><input type="checkbox" name="all" id="allcb"> All</th> -->
                        <th>#</th>
                        <th>@sortablelink('name', trans('users::menu.sidebar.form.name'))</th>
                        <th>@sortablelink('email',trans('users::menu.sidebar.form.email'))</th>
                        <th>@lang('users::menu.sidebar.form.reg_date')</th>
                        <th>@lang('users::menu.sidebar.form.mob_number')</th>
                        <th>@lang('users::menu.sidebar.form.status')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users)>0)
                        @php $i=0; @endphp
                        @foreach($users as $user)
                         @php $i++; @endphp
                        <tr>
                            <td><input type="checkbox" name="userId[]" value="{{$user->id}}"></td>
                            <td>{{ $user->fullName }}</td>
                            <td><a href="mailto:{{ $user->email }}" class="tooltips" data-original-title="Send Email">{{ $user->email }}</a></td>
                            <td>{{ $user->created_at->format(\Config::get('custom.default_date_formate')) }}</td>
                            <td>{{ ($user->phone !='') ? $user->phone : 'N/A' }}</td>
                            <td>
                                @if($user->status == 1)
                                <span class="label label-success">@lang('users::menu.sidebar.form.active')</span>
                                @else
                                <span class="label label-danger">@lang('users::menu.sidebar.form.inactive')</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="9" align="center">@lang('menu.no_record_found')</td></tr>
                    @endif
                </tbody>
            </table>
            <div class="pull-right">
            {{ $users->appends($_GET)->links("pagination::bootstrap-4") }}
            </div>
        </div>
        */ ?>
        <div class="box-header with-border">
          <div class="box-body">
            <div class="row">
                 <div class="col-md-12">
                   @include('notifications::basic.form')
                 </div>
              </div>
          </div>
          <div class="box-footer">
              <div class="row pull-right">
                    <div class="col-sm-12">
                        <button class="btn btn-primary directSubmit" type="submit" id="sendNotification">{{trans('notifications::menu.sidebar.send_notification')}}</button>
                        <button type="reset" class="btn btn-default reset">{{trans('menu.sidebar.reset')}}</button>
                     </div>
              </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </section>
@endsection
@section('uniquePageScript')
<!-- jQueryUI library -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="{{URL::to('tokeninput/src/jquery.tokeninput.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
     $("#users-input").tokenInput("{{route('notifications.getUsersLists')}}", {
        onReady: function(){ 
            $('#token-input-users-input').attr('placeholder','Search user'); 
            $('#token-input-users-input').addClass('form-control'); 
        },
        minChars: 2,
        preventDuplicates: true,
        theme: "facebook",
        hintText: "Please search user by name..",
        noResultsText: "No Record Found",
        searchingText: "Please wait..."
    });
});
 
jQuery(document).ready(function() {
  $('#allcb').change(function () {
      $('tbody tr td input[type="checkbox"]').prop('checked', $(this).prop('checked'));
  });
   jQuery(".chosen-select").chosen({
       no_results_text: "Oops, nothing found!"
    })
    $('.reset').click(function(){
        $(".chosen-select").val('').trigger("chosen:updated");
    });
}); 
</script>
<script>
$(document).ready(function() {
    var inputValue = $('input[type="radio"]').val();
    var targetBox = $(".manual");
       if(inputValue=='all'){
         $("#users-input-div").hide();
         $("#users-input").prop('required',false);
         $("#user_ids").prop('required',false);
         $('tbody tr td input[type="checkbox"]').prop('checked', false);
        }else{
          $("#users-input-div").show();
          $("#users-input").prop('required',true);
          $("#user_ids").prop('required',true);
        }
    $('input[type="radio"]').click(function() {
        var inputValue = $(this).attr("value");
        var targetBox = $(".manual");
        if(inputValue=='all'){
          $("#users-input-div").hide();
          $("#users-input").prop('required',false);
          $("#user_ids").prop('required',false);
          $('tbody tr td input[type="checkbox"]').prop('checked', false);
        }else{
          $("#users-input-div").show();
          $("#users-input").prop('required',true);
          $("#user_ids").prop('required',true);
        }
    });

    $("#SearchByName").autocomplete({
        source: "{{route('notifications.getSuggessionDeals')}}",
        minLength: 2,
        select: function(event, ui) {
            if(ui.item.id==''){ 
                $("#SearchByName").val('');
                $("#product_id").val('');
                setTimeout(function(){ 
                    $("#SearchByName").val('');
                    $("#product_id").val('');
                }, 100);
            }else{
                $("#SearchByName").val(ui.item.value);
                $("#product_id").val(ui.item.id);  
            }
        }
    }).data("ui-autocomplete")._renderItem = function( ul, item ) {
             return $( "<li class='ui-autocomplete-row'></li>" )
            .data( "item.autocomplete", item )
            .append( item.label )
            .appendTo( ul );
    };

    $("#SearchByName").keyup(function(){
      if($(this).val()==''){
        $("#product_id").val('');
      }
    });

    $('form').on('reset', function(e) {
        $("#product_id").val('');
        $(".token-input-delete-token-facebook").trigger('click');
            var inputValue = $('input[type="radio"]').val();
            var targetBox = $(".manual");
           if(inputValue=='all'){
             $("#users-input-div").hide();
             $("#user_ids").prop('required',false);
             $("#users-input").prop('required',false);
            }else{
              $("#users-input-div").show();
              $("#user_ids").prop('required',true);
              $("#users-input").prop('required',true);
            }
    });
});
</script>
@endsection