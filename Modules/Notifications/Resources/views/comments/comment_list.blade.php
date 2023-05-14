@extends('admin.layouts.master')
@section('title', "Product Comments" .app_name(). " :: Admin")
@section('content')
    <link rel="stylesheet" href="{{URL::to('css/jquery.ajax-combobox.css')}}" type="text/css" />
   <section class="content-header">
      <h1><i class="fa fa-comment"></i>
        Product Comments
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('backend.dashboard')}}">{{trans('menu.sidebar.dashboard')}}</a></li>
        <li><a href="{{route('commentnotifications.index')}}">Product Notifications</a></li>
        <li class="active">Product Comments</li>
      </ol>
   </section>
    <section class="content">
      <div class="box box-success">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-widget">
              <div class="box-header with-border">
                <div class="user-block">
                  <img class="img-circle" src="{{$product->PublisherImage}}" alt="User Image">
                  <span class="username"><a href="{{route('subadmin.show',$product->user->slug)}}">{{$product->PublisherName}}</a></span>
                  <span class="description">{{ date_format($product->created_at,"d F,Y - h:i A") }} (EDT)</span>
                </div>
              </div>
              <div class="box-body deal-list-pic">
                <div class="attachment-block clearfix">
                  <a href="{{ route('details',$product->slug) }}">
                    <img class="attachment-img coverpicforcomment" src="{{ ($product->image!='')?$product->S3Url:'' }}" alt="Deal Photo">
                    <div class="deal-bacth">
                        @if($product->deal_of_the_day)
                            <img src="{{ asset('/front/images/deal-batch-1.svg') }}" alt="">
                        @endif
                    </div>
                    @if($product->tag)
                        <img src="{{ asset('/images/'.config::get('custom.deal_tags_color')[$product->tag]) }}" alt="Tag" class="deal-badge">
                    @endif
                  </a>
                    <div class="attachment-pushed">
                      <h4 class="attachment-heading"><a href="{{ route('details',$product->slug) }}">{{ $product->name }}</a></h4>
                      <div class="attachment-text">
                       {!! $product->description !!}
                      </div>
                    </div>
                  </div>
                <span class="pull-right text-muted">{{$product->likes()}} likes - {{$product->comments()->count()}} comments</span>
              </div>
              <div class="box-footer ">
                {!! Form::open(['route' => 'commentnotifications.addComments','class'=>'','id'=>'F_AddComment',]) !!}
                  <img class="img-responsive img-circle img-sm" src="{{ auth()->user()->S3Url}}" alt="Alt Text">
                  <input type="hidden" name="product_id" value="{{ $product->id }}" />
                  <div class="img-push">
                    <div class="cmntarea cmntareamain ermsg">
                      <textarea  name="comment" data-msg-required="Please enter your comment" id="commentt" class="form-control input-sm textarea"  placeholder="Enter your comment" rows="3" cols="450" required ></textarea>
                    </div>
                  </div>
                  <div class="form-group text-right" style="padding-right: 5px;"><br>
                     <button class="btn btn-warning directSubmit" id="AddComment" type="submit">Comment</button>
                  </div>
                {!! Form::close() !!}
              </div>
              <div class="box-footer box-comments comment-section-list">
                @include('notifications::comments.commentsDisplay', ['commentsArray' => $product->comments()->paginate(10), 'post_id' => $product->id])
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
@section('uniquePageScript')
<script type="text/javascript" src="{{URL::to('js/jquery.ajax-combobox.min.js')}}"></script>
<script>
var data = <?php echo $tagUsers; ?>;
var tagUrl  = "{{route('commentnotifications.getUsersListForTag')}}";
$(function() {
    $(".textarea").each(function() {
      var _id =  this.id;
      $('#'+_id).ajaxComboBox(
        data,
        {
          //source: tagUrl,
          plugin_type: 'textarea',
          search_field: 'username',
          select_only: true,
          tags: [
            {
              pattern: ['@', ''],
              field: 'username',
              space: [true, true],
            }
          ]
        }
      );
    });
});
</script>
@endsection
