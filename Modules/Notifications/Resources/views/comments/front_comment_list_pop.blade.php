@foreach($comments as $comment)
<div class="d-flex flex-column comment-section-list">
    <div class="p-0">
        <div class="d-flex flex-row user-info">
            <img class="rounded-circle" src="{{@$comment->PublisherImage}}" width="50">
            <div class="d-flex flex-row justify-content-between w-100">
                <div class="d-flex flex-column justify-content-center ml-2">
                    <span class="d-block name">{{$comment->PublisherName}}</span>
                        <span class="author">
                            @if($comment->IsAdminWithRole)
                                {{$comment->IsAdminWithRole}} <img src="{{ asset('/images/verified.svg') }}" alt="" class="verified">
                            @endif
                            &nbsp;
                        </span>
                </div>
                <div class="d-flex flex-column justify-content-center ml-2">
                    <span class="date">{{$comment->created_at->diffForHumans()}}</span>
                </div>
            </div>
        </div>
        <div class="comments-view">
            <div class="mt-2">
                <p class="comment-text">{{ $comment->comment }}</p>
            </div>
            <div class="text-right">
                <span class="likes-text"><img src="{{ asset('/images/like.svg') }}" alt="" class="likes">{{$comment->likes()}}</span>
            </div>
            @if($comment->replies()->count() > 0)
            <div class="d-flex mb-3 replyblk{{$comment->id}}">
                <a href="javascript:;" class="more-comments replypoptoggle" data-cmntid="replyblk{{$comment->id}}">View {{$comment->replies()->count()}} More Replies</a>
            </div>
            @endif
        </div>
    </div>
    @if($comment->replies()->count() > 0)
        <div id="replyblk{{$comment->id}}" style="display: none;">
        @foreach($comment->replies as $reply)
            <div class="pl-5 reply @if($loop->first) first-child @endif">
                <div class="d-flex flex-row user-info">
                    <img class="rounded-circle" src="{{$reply->PublisherImage}}" width="50">
                    <div class="d-flex flex-row justify-content-between w-100">
                        <div class="d-flex flex-column justify-content-center ml-2">
                            <span class="d-block name">{{$reply->PublisherName}}</span>
                            <span class="author">
                                @if($reply->IsAdminWithRole)
                                    {{$reply->IsAdminWithRole}} <img src="{{ asset('/images/verified.svg') }}" alt="" class="verified">
                                @endif
                                &nbsp;
                            </span>
                        </div>
                        <div class="d-flex flex-column justify-content-center ml-2">
                            <span class="date">{{$reply->created_at->diffForHumans()}}</span>
                        </div>
                    </div>
                </div>
                <div class="comments-view">
                    <div class="mt-2">
                        <p class="comment-text">{{ $reply->comment }}</p>
                    </div>
                    <div class="text-right">
                        <span class="likes-text"><img src="{{ asset('/images/like.svg') }}" alt="" class="likes">{{$reply->likes()}}</span>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endif
</div>
@endforeach