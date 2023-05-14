<?php

namespace App\Repositories\Product\Comment;

use DB, Mail;
use config, File;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\PushNotifications;
use Modules\Products\Entities\Products;
use Modules\Products\Entities\ProductComment;
use Modules\Products\Entities\CommentsLikeDislikes;

class ProductCommentRepository implements ProductCommentRepositoryInterface
{

    function __construct(User $User, Products $Products, ProductComment $ProductComment, CommentsLikeDislikes $CommentsLikeDislikes, PushNotifications $PushNotifications)
    {
        $this->User = $User;
        $this->Products = $Products;
        $this->ProductComment = $ProductComment;
        $this->CommentsLikeDislikes = $CommentsLikeDislikes;
        $this->PushNotifications = $PushNotifications;
    }

    public function getUsersListForTag($request)
    {
        $validator = Validator::make($request->all(), [
            //'keyword' => 'min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $users = $this->User->select('id', 'name', 'username', 'image')->where('status', 1)->orderBy('id', 'DESC');
        if (auth()->user()) {
            $users =  $users->where('id', '!=', auth()->user()->id);
        }
        if ($request->get('keyword')) {
            $users = $users->where('name', 'LIKE', '%' . $request->get('keyword') . '%')
                ->orWhere('username', 'LIKE', '%' . $request->get('keyword') . '%');
        }
        $users = $users->paginate(20);
        $response = paginationFormat($users);
        $response['status_code'] = 200;
        $response['message'] = 'Users listing.';
        if (count($users) > 0) {
            foreach ($users as $key => $list) {
                $response['data']['usernames'][$key] = $list;
                $response['data']['usernames'][$key]['user_profile_image'] = $list->PicturePath;
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'There is no record available.';
            $response['data'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function addComment($request)
    {   //for now guest functionalty not using in app but we added code in advanced for future use
        if ($request->headers->get('IsGguest') && $request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'device_uid' => 'required',
                'comment'    => 'required',
                'tag_user_id' => 'array',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = 'Comment Added successfully';
            if ($comment = ProductComment::create($request->all())) {
                if ($request->tag_user_id && count($request->tag_user_id) > 0) {
                    $tagsArray = [];
                    foreach ($request->tag_user_id as $key => $tag) {
                        $tagsArray[$key]['comment_id'] = $comment->id;
                        $tagsArray[$key]['user_id'] = @$tag['id'];
                        $tagsArray[$key]['username'] = @$tag['userName'];
                        $tagsArray[$key]['created_at'] = utctodtc_4now();
                        $tagsArray[$key]['updated_at'] = utctodtc_4now();
                    }
                    $comment->tags()->insert($tagsArray);
                }
                $response['lastComment']['id'] = $comment->id;
                $response['lastComment']['comment'] = $comment->comment;
                $response['lastComment']['created_at'] = $comment->created_at;
                $response['lastComment']['created_view_date'] = $comment->created_at->diffForHumans();
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['lastComment']['isLike'] = $comment->IsUserLike;
                } else {
                    $response['lastComment']['isLike'] = $comment->GuestIsLikeOnComment($comment->id, $request->device_uid);
                }
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['lastComment']['isDisLike'] = $comment->IsUserDisLike;
                } else {
                    $response['lastComment']['isDisLike'] = $comment->GuestIsDisLikeOnComment($comment->id, $request->device_uid);
                }
                $response['lastComment']['totalLikes'] = $comment->likes();
                $response['lastComment']['totalDisLikes'] = $comment->dislikes();
                $response['lastComment']['totalReplies'] = $comment->replies()->count();
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['lastComment']['isReplyOnComment'] = $comment->IsReplyOnComment;
                } else {
                    $response['lastComment']['isReplyOnComment'] = $comment->GuestIsReplyOnComment($comment->id, $request->device_uid);
                }
                $response['lastComment']['replies'] = [];
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Comment Not Added',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'comment' => 'required',
                'tag_user_id' => 'array',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = '';
            $request['user_id'] = auth()->user()->id;
            if ($comment = ProductComment::create($request->all())) {

                $filleable['title'] = $comment->user->name . " comment on " . $comment->product->name;
                $filleable['body'] = $comment->comment;
                $filleable['product_id'] = $comment->product_id;
                $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
                $filleable['userId'] = ($comment->product) ? $comment->product->user->id : "";
                $filleable['type'] = 'singleuser';
                $filleable['notification_type'] = 'comment';
                $filleable['comment_id'] = $comment->id;
                $this->PushNotifications->create($filleable);

                if ($request->tag_user_id && count($request->tag_user_id) > 0) {
                    $tagsArray = [];
                    $tokenssss = [];
                    foreach ($request->tag_user_id as $key => $tag) {
                        $tagsArray[$key]['comment_id'] = $comment->id;
                        $tagsArray[$key]['user_id'] = @$tag['id'];
                        $tagsArray[$key]['username'] = @$tag['userName'];
                        $tagsArray[$key]['created_at'] = utctodtc_4now();
                        $tagsArray[$key]['updated_at'] = utctodtc_4now();
                        $tagUser = User::whereNotNull('device_token')->where('id', @$tag['id'])->first();
                        if ($tagUser) {
                            $tokenssss[] = $tagUser->device_token;
                        }
                        $filleable['userId'] = @$tag['id'];
                        $filleable['title'] = $comment->user->name . " tagged you in a deal " . $comment->product->name;
                        if ($comment->user->id != @$tag['id']) {
                            $this->PushNotifications->create($filleable);
                        }
                    }
                    $comment->tags()->insert($tagsArray);
                    if ($comment->user->id != @$tag['id']) {
                        $this->sendPushNotification($filleable, $tokenssss);
                    }
                }
                $response['message'] = 'Comment Added successfully';
                $response['lastComment']['id'] = $comment->id;
                $response['lastComment']['username'] = $comment->user->name;
                $response['lastComment']['user_pic'] = $comment->user->PicturePath;
                $response['lastComment']['comment'] = $comment->comment;
                $response['lastComment']['created_at'] = $comment->created_at;
                $response['lastComment']['created_view_date'] = $comment->created_at->diffForHumans();
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['lastComment']['isLike'] = $comment->IsUserLike;
                } else {
                    $response['lastComment']['isLike'] = $comment->GuestIsLikeOnComment($comment->id, $request->device_uid);
                }
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['lastComment']['isDisLike'] = $comment->IsUserDisLike;
                } else {
                    $response['lastComment']['isDisLike'] = $comment->GuestIsDisLikeOnComment($comment->id, $request->device_uid);
                }
                $response['lastComment']['totalLikes'] = $comment->likes();
                $response['lastComment']['totalDisLikes'] = $comment->dislikes();
                $response['lastComment']['totalReplies'] = $comment->replies()->count();
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['lastComment']['isReplyOnComment'] = $comment->IsReplyOnComment;
                } else {
                    $response['lastComment']['isReplyOnComment'] = $comment->GuestIsReplyOnComment($comment->id, $request->device_uid);
                }
                $response['lastComment']['replies'] = [];
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Comment Not Added',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        }
    }




    public function getProductComments($request)
    {
        if ($request->headers->get('IsGguest') && $request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'slug' => 'required',
                'device_uid' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'slug' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        }

        if (Products::where('slug', $request->slug)->exists()) {
            $product = Products::with(['comments', 'comments.replies', 'comments.user'])->where('slug', $request->slug)->first();
            if ($request->headers->get('IsGguest') == 'false') {
                $response['isComment'] = $product->IsCommentProduct;
            } else {
                $response['isComment'] = $product->GuestIsCommentProduct($product->id, $request->device_uid);
            }
            $productComment = $product->comments()->paginate(20);
            $response = paginationFormat($productComment);
            if (count($productComment) > 0) {
                foreach ($productComment as $ckey => $comment) {
                    $response['comments'][$ckey]['id'] = $comment->id;
                    $response['comments'][$ckey]['product_id'] = $comment->product_id;
                    $response['comments'][$ckey]['parent_id'] = $comment->parent_id;
                    $response['comments'][$ckey]['comment'] = $comment->comment;
                    $response['comments'][$ckey]['created_at'] = $comment->created_at;
                    $response['comments'][$ckey]['username'] = $comment->user->name;
                    $response['comments'][$ckey]['user_pic'] = $comment->user->PicturePath;
                    $response['comments'][$ckey]['IsAdminWithRole'] = $comment->IsAdminWithRole;
                    $response['comments'][$ckey]['created_view_date'] = $comment->created_at->diffForHumans();
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['comments'][$ckey]['isLike'] = $comment->IsUserLike;
                    } else {
                        $response['comments'][$ckey]['isLike'] = $comment->GuestIsLikeOnComment($comment->id, $request->device_uid);
                    }
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['comments'][$ckey]['isDisLike'] = $comment->IsUserDisLike;
                    } else {
                        $response['comments'][$ckey]['isDisLike'] = $comment->GuestIsDisLikeOnComment($comment->id, $request->device_uid);
                    }
                    $response['comments'][$ckey]['totalLikes'] = $comment->likes();
                    $response['comments'][$ckey]['totalDisLikes'] = $comment->dislikes();
                    $response['comments'][$ckey]['totalReplies'] = $comment->replies()->count();
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['comments'][$ckey]['isReplyOnComment'] = $comment->IsReplyOnComment;
                    } else {
                        $response['comments'][$ckey]['isReplyOnComment'] = $comment->GuestIsReplyOnComment($comment->id, $request->device_uid);
                    }
                    //$productCommentReply = $comment->replies()->paginate(5);
                    //$response['comments'][$ckey]['replies'] = paginationFormat($productCommentReply);
                    $productCommentReply = $comment->replies;
                    if (count($productCommentReply) > 0) {
                        foreach ($productCommentReply as $rkey => $repcomment) {
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['id'] = $repcomment->id;
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['product_id'] = $repcomment->product_id;
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['parent_id'] = $repcomment->parent_id;
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['comment'] = $repcomment->comment;
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['IsAdminWithRole'] = $repcomment->IsAdminWithRole;;
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['created_at'] = $repcomment->created_at;
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['created_view_date'] = $repcomment->created_at->diffForHumans();
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['username'] = ($repcomment->user) ? $repcomment->user->name : 'Guest User';
                            $response['comments'][$ckey]['replies']['comments'][$rkey]['user_pic'] = ($repcomment->user) ? $repcomment->user->PicturePath : onerrorReturnImage();
                        }
                    } else {
                        $response['comments'][$ckey]['replies'] = [];
                    }
                }
            } else {
                $response['comments'] = [];
            }
            return response()->json([
                'status_code' => 200,
                'message' => 'Get Comment Lists',
                'data' => $response
            ], 200)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(200);
        } else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Not Found',
                'data' => []
            ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
        }
    }

    public function addCommentLikeDislike($request)
    {
        if ($request->headers->get('IsGguest') && $request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:like,dislike',
                'type_value' => 'required|in:0,1',
                'product_id' => 'required|exists:products,id',
                'comment_id' => 'required|exists:product_comments,id',
                'device_uid' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = '';
            if (Products::where('id', $request->product_id)->exists()) {
                if ($request->type == 'like') {
                    $request['like'] = 1;
                    $exists = $this->CommentsLikeDislikes->where('device_uid', $request->device_uid)->where('product_id', $request->product_id)->where('comment_id', $request->comment_id)->delete();
                    if ($request->type_value == 1) {
                        $likerecord = $this->CommentsLikeDislikes->create($request->all());
                        $mainComent = $likerecord->comment;
                        $tokenOwner = [];
                        if ($mainComent->user && ($mainComent->user->id != auth()->user()->id)) {
                            $filleable['title'] = 'You have received 1 new like on your comment ' . $mainComent->comment;
                            $filleable['body'] =  $mainComent->comment;
                            $filleable['product_id'] = $mainComent->product_id;
                            $filleable['slug'] = ($mainComent->product) ? $mainComent->product->slug : "";
                            $filleable['userId'] = $mainComent->user->id;
                            $filleable['type'] = 'singleuser';
                            $filleable['notification_type'] = 'comment';
                            $filleable['comment_id'] = $mainComent->id;
                            $this->PushNotifications->create($filleable);

                            if ($mainComent->user->device_token) {
                                $tokenOwner[] = $mainComent->user->device_token;
                                $this->sendPushNotification($filleable, $tokenOwner);
                            }
                        }
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['message'] = 'Comment like successfully';
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();
                    } else {
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['message'] = 'Comment unlike successfully';
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();
                    }
                } else {
                    $exists = $this->CommentsLikeDislikes->where('device_uid', $request->device_uid)->where('product_id', $request->product_id)->where('comment_id', $request->comment_id)->delete();
                    if ($request->type_value == 1) {
                        $request['dislike'] = 1;
                        $this->CommentsLikeDislikes->create($request->all());
                        $response['message'] = 'Comment dislike successfully';
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();
                    } else {
                        $response['message'] = 'Product undislike successfully';
                        $product = $this->Products->find($request->product_id);
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }
                }
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Record Not Found',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:like,dislike',
                'type_value' => 'required|in:0,1',
                'product_id' => 'required|exists:products,id',
                'comment_id' => 'required|exists:product_comments,id',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = '';
            $request['user_id'] = auth()->user()->id;
            if (Products::where('id', $request->product_id)->exists()) {
                if ($request->type == 'like') {
                    $request['like'] = 1;
                    $exists = $this->CommentsLikeDislikes->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->where('comment_id', $request->comment_id)->delete();
                    if ($request->type_value == 1) {
                        $likerecord = $this->CommentsLikeDislikes->create($request->all());
                        $mainComent = $likerecord->comment;
                        $tokenOwner = [];
                        if ($mainComent->user && ($mainComent->user->id != auth()->user()->id)) {
                            $filleable['title'] = auth()->user()->name . ' has liked on your comment ' . $mainComent->comment;
                            $filleable['body'] =  $mainComent->comment;
                            $filleable['product_id'] = $mainComent->product_id;
                            $filleable['slug'] = ($mainComent->product) ? $mainComent->product->slug : "";
                            $filleable['userId'] = $mainComent->user->id;
                            $filleable['type'] = 'singleuser';
                            $filleable['notification_type'] = 'comment';
                            $filleable['comment_id'] = $mainComent->id;
                            $this->PushNotifications->create($filleable);
                            if ($mainComent->user->device_token) {
                                $tokenOwner[] = $mainComent->user->device_token;
                                $this->sendPushNotification($filleable, $tokenOwner);
                            }
                        }
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['message'] = 'Comment like successfully';
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();
                    } else {
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['message'] = 'Comment unlike successfully';
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();;
                    }
                } else {
                    $exists = $this->CommentsLikeDislikes->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->where('comment_id', $request->comment_id)->delete();
                    if ($request->type_value == 1) {
                        $request['dislike'] = 1;
                        $this->CommentsLikeDislikes->create($request->all());
                        $response['message'] = 'Comment dislike successfully';
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();
                    } else {
                        $response['message'] = 'Comment undislike successfully';
                        $productComment = $this->ProductComment->find($request->comment_id);
                        $response['data']['totalLikes'] = $productComment->likes();
                        $response['data']['totalDisLikes'] = $productComment->dislikes();
                    }
                }
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Record Not Found',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        }
    }

    // nirbhay sir code
    public function addCommentReplyBackup($request)
    {   //for now guest functionalty not using in app but we added code in advanced for future use
        if ($request->headers->get('IsGguest') && $request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'comment_id' => 'required|exists:product_comments,id',
                'device_uid' => 'required',
                'comment'    => 'required',
                'tag_user_id' => 'array',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = 'Reply Added successfully';
            $request['parent_id'] = $request->comment_id;
            if (ProductComment::where(['product_id' => $request->product_id, 'id' => $request->comment_id])->exists()) {
                if ($comment = ProductComment::create($request->all())) {
                    if ($request->tag_user_id && count($request->tag_user_id) > 0) {
                        $tagsArray = [];
                        foreach ($request->tag_user_id as $key => $tag) {
                            $tagsArray[$key]['comment_id'] = $comment->id;
                            $tagsArray[$key]['user_id'] = $tag['id'];
                            $tagsArray[$key]['user_id'] = @$tag['id'];
                            $tagsArray[$key]['username'] = @$tag['userName'];
                            $tagsArray[$key]['created_at'] = utctodtc_4now();
                            $tagsArray[$key]['updated_at'] = utctodtc_4now();
                        }
                        $comment->tags()->insert($tagsArray);
                    }
                    $response['lastComment']['comment'] = $comment->comment;
                    $response['lastComment']['created_at'] = $comment->created_at;

                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['lastComment']['isLike'] = $comment->IsUserLike;
                    } else {
                        $response['lastComment']['isLike'] = $comment->GuestIsLikeOnComment($comment->id, $request->device_uid);
                    }
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['lastComment']['isDisLike'] = $comment->IsUserDisLike;
                    } else {
                        $response['lastComment']['isDisLike'] = $comment->GuestIsDisLikeOnComment($comment->id, $request->device_uid);
                    }
                    $response['lastComment']['totalLikes'] = $comment->likes();
                    $response['lastComment']['totalDisLikes'] = $comment->dislikes();
                    $response['lastComment']['totalReplies'] = $comment->replies()->count();
                    $response['lastComment']['IsAdminWithRole'] = $comment->IsAdminWithRole;
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['lastComment']['isReplyOnComment'] = $comment->IsReplyOnComment;
                    } else {
                        $response['lastComment']['isReplyOnComment'] = $comment->GuestIsReplyOnComment($comment->id, $request->device_uid);
                    }
                    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
                } else {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Reply Not Added',
                        'data' => []
                    ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
                }
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Something is wrong in your comment id',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'comment_id' => 'required|exists:product_comments,id',
                'comment'    => 'required',
                'tag_user_id' => 'array',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = '';
            $request['user_id'] = (auth()->user()) ? auth()->user()->id : 0;
            $request['parent_id'] = $request->comment_id;
            if ($mainComent = ProductComment::where(['product_id' => $request->product_id, 'id' => $request->comment_id])->first()) {
                if ($comment = ProductComment::create($request->all())) {
                    $filleable['title'] = $comment->user->name . " reply on " . $comment->comment;
                    $filleable['body'] = $comment->comment;
                    $filleable['product_id'] = $comment->product_id;
                    $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
                    $filleable['userId'] = 0;
                    $filleable['type'] = 'singleuser';
                    $filleable['notification_type'] = 'comment';
                    $filleable['comment_id'] = $comment->id;

                    if ($request->tag_user_id && count($request->tag_user_id) > 0) {
                        $tagsArray = [];
                        $tokenssss = [];
                        foreach ($request->tag_user_id as $key => $tag) {
                            $tagsArray[$key]['comment_id'] = $comment->id;
                            $tagsArray[$key]['user_id'] = @$tag['id'];
                            $tagsArray[$key]['username'] = @$tag['userName'];
                            $tagsArray[$key]['created_at'] = utctodtc_4now();
                            $tagsArray[$key]['updated_at'] = utctodtc_4now();
                            $tagUser = User::whereNotNull('device_token')->where('id', @$tag['id'])->first();
                            if ($tagUser) {
                                $tokenssss[] = $tagUser->device_token;
                            }
                            $filleable['userId'] = @$tag['id'];
                            $filleable['title'] = $comment->user->name . " tagged you in a deal " . $comment->product->name;
                            $this->PushNotifications->create($filleable);
                        }
                        $comment->tags()->insert($tagsArray);
                        $this->sendPushNotification($filleable, $tokenssss);
                    }
                    $tokenOwner = [];
                    if ($mainComent->user) {
                        $filleable['title'] = $comment->user->name . " reply on your comment " . $mainComent->comment;
                        $filleable['body'] =  $comment->comment;
                        $filleable['product_id'] = $comment->product_id;
                        $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
                        $filleable['userId'] = $mainComent->user->id;
                        $filleable['type'] = 'singleuser';
                        $filleable['notification_type'] = 'comment';
                        $filleable['comment_id'] = $comment->id;
                        $this->PushNotifications->create($filleable);
                        if ($mainComent->user->device_token) {
                            $tokenOwner[] = $mainComent->user->device_token;
                            $this->sendPushNotification($filleable, $tokenOwner);
                        }
                    }
                    $response['message'] = 'Reply Added successfully';
                    $response['lastComment']['username'] = $comment->user->name;
                    $response['lastComment']['user_pic'] = $comment->user->PicturePath;
                    $response['lastComment']['comment'] = $comment->comment;
                    $response['lastComment']['created_at'] = $comment->created_at;
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['lastComment']['isLike'] = $comment->IsUserLike;
                    } else {
                        $response['lastComment']['isLike'] = $comment->GuestIsLikeOnComment($comment->id, $request->device_uid);
                    }
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['lastComment']['isDisLike'] = $comment->IsUserDisLike;
                    } else {
                        $response['lastComment']['isDisLike'] = $comment->GuestIsDisLikeOnComment($comment->id, $request->device_uid);
                    }
                    $response['lastComment']['totalLikes'] = $comment->likes();
                    $response['lastComment']['totalDisLikes'] = $comment->dislikes();
                    $response['lastComment']['totalReplies'] = $comment->replies()->count();
                    if ($request->headers->get('IsGguest') == 'false') {
                        $response['lastComment']['isReplyOnComment'] = $comment->IsReplyOnComment;
                    } else {
                        $response['lastComment']['isReplyOnComment'] = $comment->GuestIsReplyOnComment($comment->id, $request->device_uid);
                    }
                    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
                } else {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Reply Not Added',
                        'data' => []
                    ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
                }
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Something is wrong in your comment id',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        }
    }

    // amir code
    public function addCommentReply($request)
    {

        //for now guest functionalty not using in app but we added code in advanced for future use
        if ($request->headers->get('IsGguest') && $request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'comment_id' => 'required|exists:product_comments,id',
                'device_uid' => 'required',
                'comment'    => 'required',
                'tag_user_id' => 'array',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = 'Reply Added successfully';
            $request['parent_id'] = $request->comment_id;
            if (ProductComment::where(['product_id' => $request->product_id, 'id' => $request->comment_id])->exists()) {
                if ($comment = ProductComment::create($request->all())) {
                    if ($request->tag_user_id && count($request->tag_user_id) > 0) {
                        $tagsArray = [];
                        foreach ($request->tag_user_id as $key => $tag) {
                            $tagsArray[$key]['comment_id'] = $comment->id;
                            $tagsArray[$key]['user_id'] = $tag['id'];
                            $tagsArray[$key]['user_id'] = @$tag['id'];
                            $tagsArray[$key]['username'] = @$tag['userName'];
                            $tagsArray[$key]['created_at'] = utctodtc_4now();
                            $tagsArray[$key]['updated_at'] = utctodtc_4now();
                        }
                        $comment->tags()->insert($tagsArray);
                    }

                    $response['lastComment']['id'] = $comment->id;
                    $response['lastComment']['product_id'] = $comment->product_id;
                    $response['lastComment']['parent_id'] = $comment->parent_id;
                    $response['lastComment']['comment'] = $comment->comment;
                    $response['lastComment']['IsAdminWithRole'] = $comment->IsAdminWithRole;
                    $response['lastComment']['created_at'] = $comment->created_at;
                    $response['lastComment']['created_view_date'] = $comment->created_at->diffForHumans();
                    $response['lastComment']['username'] =  ($comment->user) ? $comment->user->name : 'Guest User';
                    $response['lastComment']['user_pic'] =  ($comment->user) ? $comment->user->PicturePath : onerrorReturnImage();

                    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
                } else {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Reply Not Added',
                        'data' => []
                    ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
                }
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Something is wrong in your comment id',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'comment_id' => 'required|exists:product_comments,id',
                'comment'    => 'required',
                'tag_user_id' => 'array',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message'] = '';
            $request['user_id'] = (auth()->user()) ? auth()->user()->id : 0;
            $request['parent_id'] = $request->comment_id;
            if ($mainComent = ProductComment::where(['product_id' => $request->product_id, 'id' => $request->comment_id])->first()) {
                if ($comment = ProductComment::create($request->all())) {
                    $filleable['title'] = $comment->user->name . " reply on " . $comment->comment;
                    $filleable['body'] = $comment->comment;
                    $filleable['product_id'] = $comment->product_id;
                    $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
                    $filleable['userId'] = ($comment->product) ? $comment->product->user_id : "";
                    $filleable['type'] = 'singleuser';
                    $filleable['notification_type'] = 'comment';
                    $filleable['comment_id'] = $comment->id;
                    if ($request->tag_user_id && count($request->tag_user_id) > 0) {
                        $tagsArray = [];
                        $tokenssss = [];
                        foreach ($request->tag_user_id as $key => $tag) {
                            $tagsArray[$key]['comment_id'] = $comment->id;
                            $tagsArray[$key]['user_id'] = @$tag['id'];
                            $tagsArray[$key]['username'] = @$tag['userName'];
                            $tagsArray[$key]['created_at'] = utctodtc_4now();
                            $tagsArray[$key]['updated_at'] = utctodtc_4now();
                            $tagUser = User::whereNotNull('device_token')->where('id', @$tag['id'])->first();
                            if ($tagUser) {
                                $tokenssss[] = $tagUser->device_token;
                            }
                            $filleable['userId'] = @$tag['id'];
                            $filleable['title'] = $comment->user->name . " tagged you in a deal " . $comment->product->name;
                            if ($comment->user->id != @$tag['id']) {
                                $this->PushNotifications->create($filleable);
                            }
                        }
                        $comment->tags()->insert($tagsArray);
                        if ($comment->user->id != @$tag['id']) {
                            $this->sendPushNotification($filleable, $tokenssss);
                        }
                    }


                    $tokenOwner = [];
                    if ($mainComent->user) {
                        $filleable['title'] = $comment->user->name . " replied on your comment " . $mainComent->comment;
                        $filleable['body'] =  $comment->comment;
                        $filleable['product_id'] = $comment->product_id;
                        $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
                        $filleable['userId'] = $mainComent->user->id;
                        $filleable['type'] = 'singleuser';
                        $filleable['notification_type'] = 'comment';
                        $filleable['comment_id'] = $comment->id;
                        $this->PushNotifications->create($filleable);
                        if ($mainComent->user->device_token) {
                            $tokenOwner[] = $mainComent->user->device_token;
                            $this->sendPushNotification($filleable, $tokenOwner);
                        }
                    }
                    $response['message'] = 'Reply Added successfully';
                    $response['lastComment']['id'] = $comment->id;
                    $response['lastComment']['product_id'] = $comment->product_id;
                    $response['lastComment']['parent_id'] = $comment->parent_id;
                    $response['lastComment']['comment'] = $comment->comment;
                    $response['lastComment']['IsAdminWithRole'] = $comment->IsAdminWithRole;
                    $response['lastComment']['created_at'] = $comment->created_at;
                    $response['lastComment']['created_view_date'] = $comment->created_at->diffForHumans();
                    $response['lastComment']['username'] = $comment->user->name;
                    $response['lastComment']['user_pic'] = $comment->user->PicturePath;

                    return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
                } else {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Reply Not Added',
                        'data' => []
                    ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
                }
            } else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Something is wrong in your comment id',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        }
    }

    public function getCommentsReply($request)
    {
        if ($request->headers->get('IsGguest') && $request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:product_comments,id',
                'device_uid' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:product_comments,id',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        }

        if ($comment = $this->ProductComment->where('id', $request->comment_id)->first()) {
            //$commentReply = $comment->replies()->paginate(5);
            //$response = paginationFormat($commentReply);
            $commentReply = $comment->replies;
            if (count($commentReply) > 0) {
                foreach ($commentReply as $rkey => $repcomment) {
                    $response['replies'][$rkey]['id'] = $repcomment->id;
                    $response['replies'][$rkey]['product_id'] = $repcomment->product_id;
                    $response['replies'][$rkey]['parent_id'] = $repcomment->parent_id;
                    $response['replies'][$rkey]['comment'] = $repcomment->comment;
                    $response['replies'][$rkey]['created_at'] = $repcomment->created_at;
                    $response['replies'][$rkey]['IsAdminWithRole'] = $repcomment->IsAdminWithRole;
                    $response['replies'][$rkey]['created_view_date'] = $repcomment->created_at->diffForHumans();
                    $response['replies'][$rkey]['username'] = ($repcomment->user) ? $repcomment->user->name : 'Guest User';
                    $response['replies'][$rkey]['user_pic'] = ($repcomment->user) ? $repcomment->user->PicturePath : onerrorReturnImage();
                }
            } else {
                $response['replies'] = [];
            }
            return response()->json([
                'status_code' => 200,
                'message' => 'Get Comment Reply Lists',
                'data' => $response
            ], 200)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(200);
        } else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Comment Not Found',
                'data' => []
            ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
        }
    }

    public function sendPushNotification($filleable, $tokensarray)
    {
        $push = \PushNotification::setService('fcm')
            ->setMessage([
                'notification' => [
                    'title' => $filleable['title'],
                    'body'  =>  $filleable['body'],
                    'sound' => 'default'
                ],
                'data' => [
                    'product_id' => $filleable['product_id'],
                    'product_slug' => $filleable['slug'],
                    'comment_id' => $filleable['comment_id'],
                    'type' => 'singleuser',
                    'notification_type' => $filleable['notification_type'],
                ]
            ])
            ->setApiKey(env('FCM_SERVER_KEY'))
            ->setDevicesToken($tokensarray)
            ->send()
            ->getFeedback();
    }
}
