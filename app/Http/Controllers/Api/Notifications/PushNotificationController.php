<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Models\PushNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Modules\Users\Entities\GuestUsers;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notifications\Entities\FcmNotification;

class PushNotificationController extends BaseController {

    public function __construct(PushNotifications $PushNotifications,Request $request) {
        $this->PushNotifications = $PushNotifications;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api')->except(['sendFcmNotificationForAllUsers','deleteOldUserotifications']);
        return auth()->shouldUse('api');
    }

    /**
     * Display a listing of the push notifications data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPushNotifications(Request $request) {
        if($request->headers->get('IsGguest')!='true'){
            $notifications = $this->PushNotifications->where('userId',auth()->user()->id)
                ->orWhere(function($q){
                    $q->where('userId',0)
                    ->where(['notification_type'=>'product']);
                })->where('usertype','auth')->where('created_at','>=',auth()->user()->created_at)->orderBy('id','DESC')->paginate(20);
        }else{
            $validator = Validator::make($request->all(), [
                'device_uid' => 'required|exists:guest_users,device_uid',
            ]); 
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
             $guser = GuestUsers::where('device_uid',$request->device_uid)->first();
             $notifications = $this->PushNotifications->where('userId',$guser->id)
                ->orWhere(function($q){
                    $q->where('userId',0)
                    ->where(['notification_type'=>'product']);
                })->where('usertype','guest')->where('created_at','>=',$guser->created_at)->orderBy('id','DESC')->paginate(20);
        }
        $response = paginationFormat($notifications);
        $response['status_code'] = 200;
        $response['message'] = 'Push notification listing.';
        if (count($notifications) > 0) {
            foreach ($notifications as $key => $list) {
                $response['data'][$key]['type'] = $list->type;
                $response['data'][$key]['usertype'] = $list->usertype;
                $response['data'][$key]['user_id'] = $list->userId;
                $response['data'][$key]['title'] = $list->title;
                $response['data'][$key]['body'] = $list->body;
                $response['data'][$key]['product_id'] = $list->product_id;
                $response['data'][$key]['product_slug'] = ($list->product) ? $list->product->slug : "";
                $response['data'][$key]['createdAt'] =  $list->CreatedAtTime;
                $response['data'][$key]['notification_type'] =  $list->notification_type;
                $response['data'][$key]['comment_id'] =  $list->comment_id;
            }
        }else{
            $response['status_code'] = 200;
            $response['message'] = 'There is no record available.';
            $response['data'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }    

    public function markReadNotifiction(Request $request) { 
        if($request->headers->get('IsGguest')=='false'){
            auth()->user()->update(['is_unread'=>0]);
        }else{
            $validator = Validator::make($request->all(), [
                'device_uid' => 'required|exists:guest_users,device_uid',
            ]); 
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            GuestUsers::where('device_uid',$request->device_uid)->update(['is_unread'=>0]);
        }
        $response['status_code'] = 200;
        $response['message'] = 'Notification read successfully';
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    } 

    public function sendFcmNotificationForAllUsers() {
            $pendingRecords = FcmNotification::where('status','pending')->get();
            if(count($pendingRecords)>0){
                foreach ($pendingRecords as $list) {
                    $list->status = 'inprogress';
                    $list->save();
                    $filleable['title'] = $list->title;
                    $filleable['body'] = $list->message;
                    $filleable['product_id'] = $list->product_id;
                    $filleable['slug'] = ($list->product) ? $list->product->slug : "";
                    $tokenss = User::whereNotNull('device_token')->orderBy('id','desc')->whereHas('roles', function(Builder $q) {
                        $q->where('slug','customer');
                    })->pluck('device_token','id')->chunk(700, function ($token) {   })->toArray();
                    $tokenssss = NULL;
                    $filleable['userId'] = 0;
                    $filleable['type'] = 'alluser';
                    foreach ($tokenss as $arrayKey =>$tokens) {
                         if($arrayKey == 0){
                            $notify = $this->PushNotifications->create($filleable);
                         }
                        $tokenssss = [];
                        foreach ($tokens as $userId => $tokenn) {
                            $user = User::find($userId);
                            if($user->notification_status == 1){
                                $tokenssss[] = $tokenn;
                            }
                            $filleable['userId'] = $userId;
                            //$notify = $this->PushNotifications->create($filleable);
                            if($user){
                                $user->is_unread = 1;
                                $user->save();
                            }
                        }
                       //echo "<pre>"; print_r($tokenssss);
                       $this->sendPushNotification($filleable,$tokenssss);
                    }

                    //Send Notification for guest users
                    $guetokenss = GuestUsers::whereNotNull('device_token')->orderBy('id','desc')->pluck('device_token','id')->chunk(700, function ($token) {   })->toArray();
                    $gtokenssss = NULL;
                    $filleable['userId'] = 0;
                    $filleable['type'] = 'alluser';
                    $filleable['usertype'] = 'guest';
                    foreach ($guetokenss as $arrayKey =>$tokens) {
                        if($arrayKey == 0){
                            $notify = $this->PushNotifications->create($filleable);
                        }
                       $gtokenssss = [];
                        foreach ($tokens as $userId => $tokenn) {
                            $user = GuestUsers::find($userId);
                            if($user->notification_status == 1){
                                $gtokenssss[] = $tokenn;
                            }
                            $filleable['userId'] = $userId;
                            //$notify = $this->PushNotifications->create($filleable);
                            if($user){
                                $user->is_unread = 1;
                                $user->save();
                            }
                        }
                       $this->sendPushNotification($filleable,$gtokenssss);
                    }
                    $list->delete();
                }
                $response['status_code'] = 200;
                $response['message'] = 'Sent successfully';
                return response()->json($response, $response['status_code']);
            }
            $response['status_code'] = 200;
            $response['message'] = 'No Record Found';
            return response()->json($response, $response['status_code']);
    }

    public function sendPushNotification($filleable,$tokensarray) {
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
                ->setDevicesToken($tokensarray)
                ->send()
                ->getFeedback();
    }

    public function deleteOldUserotifications() {
            PushNotifications::whereDate('created_at', '<=', now()->subMonths(2))->chunk(1000, function ($notifications) {
                foreach($notifications as $record) {
                    $record->delete();
                }
            });
            $response['status_code'] = 200;
            $response['message'] = 'Old Notifications deleted successfully';
            return response()->json($response, $response['status_code']);
    }
}

