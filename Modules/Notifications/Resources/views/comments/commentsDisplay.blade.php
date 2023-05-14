@foreach($commentsArray as $comment)
  <div class="box-comment" @if($comment->parent_id != null) style="margin-left:40px;" @endif>
      @if($comment->user )
        @if($comment->user->hasRole('subadmin'))
        <a href="{{route('subadmin.show',$comment->user->slug)}}">
        @else
         <a href="{{route('users.show',$comment->user->slug)}}">
        @endif
      @endif
    <img class="img-circle img-sm" src="{{ @$comment->user->S3Url}}" alt="User Image" onerror="this.src='{{onerrorReturnImage()}}'">
    </a>
    <div class="comment-text">
          <span class="username">
            @if($comment->user)
              {{ ucfirst($comment->user->name) }}
            @else
              Guest User
            @endif
            <span class="text-muted pull-right">{{ date_format($comment->created_at->subHours(4),"d F,Y - h:i A") }} (EDT)</span>
          </span>
            @if($comment->IsAdminWithRole)
                <span class="author">{{$comment->IsAdminWithRole}} <img src="{{ asset('/images/verified.svg') }}" alt="" class="verified"></span><br>
            @endif
          {{ $comment->comment }}
          <form method="POST" action="{{ route('commentnotifications.destroy',[$comment->id]) }}" accept-charset="UTF-8" style="display:inline" class="dele_{{$comment->id}}">
            <input name="_method" value="DELETE" type="hidden">
                @csrf
                <span class="pull-right">
                     &nbsp;<a href="javascript:;" id="dele_{{$comment->id}}" data-toggle="tooltip" title="Delete" type="button"  data-placement="top" name="Delete" class="delete_action tble_button_st tooltips" Onclick="return ConfirmDeleteLovi(this.id,this.name,this.name);" ><i class="fa fa-trash-o" title="Delete"></i>
                    </a>
                 </span>
            </form>
    </div>
    @if($comment->parent_id == null)
    <div class="box-footer padtop20"> 
      {!! Form::open(['route' => 'commentnotifications.addCommentReply','id'=>'F_AddReply'.$comment->id]) !!}
        <img class="img-responsive img-circle img-sm" src="{{ auth()->user()->S3Url}}" alt="Alt Text">
        <div class="img-push">
          <div class="cmntarea ermsg">
            <textarea name="comment" data-msg-required="Please enter your reply" id="reply{{$comment->id}}" class="form-control input-sm textarea w-100"  placeholder="Enter your reply" rows="3" required ></textarea>
          </div>
        </div>
        <input type="hidden" name="product_id" value="{{ $post_id }}" />
        <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
        <div class="form-group text-right" style="padding-right: 5px;"><br>
          <button class="btn btn-warning directSubmit" id="AddReply{{$comment->id}}" type="submit">Reply</button>
        </div>
      {!! Form::close() !!}
    </div>
    @endif
    @include('notifications::comments.commentsDisplay', ['commentsArray' => $comment->replies])
  </div>
@endforeach
@if($commentsArray instanceof \Illuminate\Pagination\LengthAwarePaginator )
  <div class="pull-right">
  {{ $commentsArray->appends($_GET)->links("pagination::bootstrap-4") }}
  </div>
@endif


