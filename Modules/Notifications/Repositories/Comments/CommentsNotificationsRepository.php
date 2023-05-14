<?php

namespace Modules\Notifications\Repositories\Comments;

use DB, Mail, Session;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Edujugon\PushNotification\PushNotification;
use Modules\Notifications\Entities\FcmNotification;
use App\Models\PushNotifications;
use Modules\Products\Entities\Products;
use Modules\Products\Entities\ProductComment;
use Modules\Products\Entities\CommentsLikeDislikes;

class CommentsNotificationsRepository implements CommentsNotificationsRepositoryInterface
{

    public $PushNotifications;

    function __construct(PushNotifications $PushNotifications, User $User, Products $Products, ProductComment $ProductComment)
    {
        $this->PushNotifications = $PushNotifications;
        $this->User = $User;
        $this->Products = $Products;
        $this->ProductComment = $ProductComment;
    }

    public function getProductBySlug($slug)
    {
        return $this->Products->where('slug', $slug)->first();
    }

    public function getCommentById($id)
    {
        return $this->ProductComment->where('id', $id)->first();
    }

    public function getAllCommentNotifications($request)
    { 
        $notifications = $this->PushNotifications->whereHas('product')->orderBy('id', 'desc')->whereNotIn('notification_type', ['product']);
        if ($request->get('isread')) {
            $rStatus = ($request->get('isread') == 'read')  ? $rStatus = 1 : 0;
            $notifications->where('is_read', $rStatus);
        }
        if ($request->get('name')) {
            $notifications->whereHas('product', function (Builder $q) use ($request) {
                $q->orWhere('products.name', 'LIKE', "%" . $request->get('name') . "%");
            });
        }
        if ($request->get('username')) {
            $notifications->whereHas('user', function (Builder $q) use ($request) {
                $q->where('users.name', 'LIKE', "%" . $request->get('username') . "%");
            });
        }
        return $notifications->sortable('id')->paginate(30);
    }

    public function getProductComments($request, $slug)
    {
        $product = $this->Products->where('slug', $slug)->first();
        if ($product) {
            $product->notifications()->update(['is_read' => 1, 'is_read_date' => now()]);
        }
        return $product;
    }

    public function addComments($request)
    {
        $response['status_code'] = 200;
        $response['type'] = 'success';
        $response['message'] = '';
        $request['user_id'] = auth()->user()->id;
        if ($comment = ProductComment::create($request->all())) {
            $filleable['title'] = $comment->user->name . " comment on " . $comment->product->name;
            $filleable['body'] = $comment->comment;
            $filleable['product_id'] = $comment->product_id;
            $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
            $filleable['userId'] = auth()->user()->id;
            $filleable['type'] = 'singleuser';
            $filleable['notification_type'] = 'comment';
            $filleable['comment_id'] = $comment->id;
            $notify = $this->PushNotifications->create($filleable);
            $commnetMessage = $request->comment;
            $pattern = '/@([a-zA-Z0-9-]+)/'; // the pattern to extract all nicknames (without @)
            preg_match_all($pattern, $commnetMessage, $matches);
            $users = User::whereNotNull('device_token')->whereIn('username', $matches[1])->get();
            if (count($users) > 0) {
                $tagsArray = [];
                $tokenssss = [];
                foreach ($users as $key => $user) {
                    $tagsArray[$key]['comment_id'] = $comment->id;
                    $tagsArray[$key]['user_id'] = $user->id;
                    $tagsArray[$key]['username'] = $user->userName;
                    $tagsArray[$key]['created_at'] = utctodtc_4now();
                    $tagsArray[$key]['updated_at'] = utctodtc_4now();
                    $tokenssss[] = $user->device_token;
                    $filleable['userId'] = $user->id;
                    $filleable['title'] = $comment->user->name . " tagged you in a deal " . $comment->product->name;
                    $this->PushNotifications->create($filleable);
                }
                $comment->tags()->insert($tagsArray);
                $this->sendPushNotification($filleable, $tokenssss);
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
            $response['reset'] = 'true';
            $response['url'] = url()->previous();
            return $response;
        } else {
            $response['message'] = 'Comment Not Added';
            $response['type'] = 'error';
            $response['status_code'] = 400;
            return $response;
        }
    }

    public function addCommentReply($request)
    {
        $response['status_code'] = 200;
        $response['type'] = 'success';
        $response['message'] = '';
        $request['user_id'] = auth()->user()->id;
        $request['parent_id'] = $request->comment_id;
        if ($mainComent = ProductComment::where(['product_id' => $request->product_id, 'id' => $request->comment_id])->first()) {
            if ($comment = ProductComment::create($request->all())) {
                $filleable['title'] = $comment->user->name . " reply on " . $comment->product->name;
                $filleable['body'] = $comment->comment;
                $filleable['product_id'] = $comment->product_id;
                $filleable['slug'] = ($comment->product) ? $comment->product->slug : "";
                $filleable['userId'] = 0;
                $filleable['type'] = 'singleuser';
                $filleable['notification_type'] = 'comment';
                $filleable['comment_id'] = $comment->id;

                $commnetMessage = $request->comment;
                $pattern = '/@([a-zA-Z0-9-]+)/'; // the pattern to extract all nicknames (without @)
                preg_match_all($pattern, $commnetMessage, $matches);
                $users = User::whereNotNull('device_token')->whereIn('username', $matches[1])->get();
                if (count($users) > 0) {
                    $tagsArray = [];
                    $tokenssss = [];
                    foreach ($users as $key => $user) {
                        $tagsArray[$key]['comment_id'] = $comment->id;
                        $tagsArray[$key]['user_id'] = $user->id;
                        $tagsArray[$key]['username'] = $user->username;
                        $tagsArray[$key]['created_at'] = utctodtc_4now();
                        $tagsArray[$key]['updated_at'] = utctodtc_4now();

                        $tokenssss[] = $user->device_token;
                        $filleable['userId'] = $user->id;
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
                $response['reset'] = 'true';
                $response['url'] = url()->previous();
                return $response;
            } else {
                $response['message'] = 'Reply Not Added';
                $response['type'] = 'error';
                $response['status_code'] = 400;
                return $response;
            }
        } else {
            $response['message'] = 'Something is wrong in your comment id';
            $response['type'] = 'error';
            $response['status_code'] = 400;
            return $response;
        }
    }

    public function sendPushNotification($filleable, $tokens)
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
                    'type' => 'singleuser'
                ]
            ])
            ->setApiKey(env('FCM_SERVER_KEY'))
            ->setDevicesToken($tokens)
            ->send()
            ->getFeedback();
    }

    public function getTagUserLists($request)
    {
        $request['q'] = @$request->get('q_word')[0];
        $searchTerm = $request->get('q');
        $users = $this->User->select('id', 'username')->orderBy('name', 'ASC')->whereHas('roles', function (Builder $q) {
            $q->where('slug', 'customer');
        });
        if ($request->get('q')) {
            $users = $users->where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', "%" . $request->get('q') . "%")
                    ->orWhere('name', 'LIKE', "%" . $request->get('q') . "%");
            });
        }
        $users = $users->get()->toArray();
        return $users;
    }

    public function destroy($id)
    {
        $comment = $this->ProductComment->where('id', $id)->first();
        if ($comment) {
            $comment->delete();
            $comment->replies()->delete();
        }
        return true;
    }

    public function getProductsCommnetsForFront($request, $slug)
    {
        $product = $this->Products->where('slug', $slug)->first();
        $comments = $product->comments()->paginate(100);
        if ($request->ajax()) {
            return view('notifications::comments.front_comment_list_pop', compact('comments'))->render();
        }
    }
}
