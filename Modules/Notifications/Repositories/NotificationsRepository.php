<?php

namespace Modules\Notifications\Repositories;

use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PushNotifications;
use Edujugon\PushNotification\PushNotification;
use Modules\Notifications\Entities\FcmNotification;
use Modules\Products\Entities\Products;

class NotificationsRepository implements NotificationsRepositoryInterface {

    public $PushNotifications;

    function __construct(PushNotifications $PushNotifications,User $User) {
        $this->PushNotifications = $PushNotifications;
        $this->User = $User;
    }

    public function getRecord($id)
    {
      return $this->PushNotifications->findorFail($id);
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $lists = $this->PushNotifications->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->orderBy('id','desc')->limit(3000)->get();  
            return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['notifications.destroy', [$list->id]], 'edit'=>['notifications.edit', [$list->id]],]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    return $delete;
                })  
                ->editColumn('body', function($list){
                    return \Illuminate\Support\Str::limit($list->body, 600, '...'); 
                }) 
                ->editColumn('username', function($list){
                    return ($list->user) ? $list->user->name : 'N/A'; 
                })
                ->editColumn('created_at', function($list){
                    return date_format($list->created_at->subHours(4),"m/d/Y");
                })
                ->rawColumns(['action','body'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('title');
            $filleable['body'] = $request->get('message');
            $filleable['product_id'] = $request->get('product_id');
            $product = Products::find($request->get('product_id'));
            $filleable['slug'] = ($product) ? $product->slug : "";
            if($request->get('selection')=='manual'){
                    $request->validate(
                        [
                            'userId' => 'required',
                        ]
                    );
                $userIdsIn = explode(',',$request->get('userId'));
                if(count($userIdsIn)>0){
                    $tokens = User::whereNotNull('device_token')->where('notification_status',1)->whereIn('id',$userIdsIn)->pluck('device_token')->toArray();
                    foreach ($userIdsIn as $key => $userId) {
                        $filleable['userId'] = $userId;
                        $notify = $this->PushNotifications->create($filleable);
                        $user = User::find($userId);
                        if($user){
                            $user->is_unread = 1;
                            $user->save();
                        }
                    } 
                    $this->sendPushNotification($filleable,$tokens);
                }
            }elseif($request->get('selection')=='all'){
                FcmNotification::create($request->all());
            }
            $response['reset'] = 'true';
            $response['status_code'] = 200;
            $response['message'] = trans('flash.success.notifications_send_successfully');
            $response['type'] = 'success';
             return $response;  
            
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.notifications_send_not_send_successfully');
            $response['type'] = 'error';
             return $response;  
        }        
    }

    public function edit($request,$id)
    {
        return $this->PushNotifications->findorFail($id);
    } 

    public function resendNotifications($request,$id)
    {
        try {
            $filleable = $request->only('title');
            $filleable['body'] = $request->get('message');
            if(count($request->get('userId'))>0){
                foreach ($request->get('userId') as $key => $userId) {
                    $tokens = User::whereNotNull('device_token')->where('notification_status',1)->whereIn('id',$request->get('userId'))->pluck('device_token')->toArray();
                    $filleable['userId'] = $userId;
                    $notify = $this->PushNotifications->create($filleable);
                    $user = User::find($userId);
                    if($user){
                        $user->is_unread = 1;
                        $user->save();
                    }
                } 
                $this->sendPushNotification($filleable,$tokens);
            }
            Session::flash('success', trans('flash.success.notifications_send_successfully'));
            return redirect()->route('notifications.index');
        }catch (Exception $ex) {
            Session::flash('error', trans('flash.error.notifications_send_not_successfully'));
            return redirect()->route('notifications.index');
        }      
    }

    public function destroy($id)
    {
      return $this->PushNotifications->destroy($id);
    }


    public function getAllUsersPluck()
    {
        return $this->User->whereHas('roles', function(Builder $q) {
                    $q->where('slug','customer');
                })->pluck('name','id')->toArray();
    }

    public function getAll($request,$role)
    {
        $users = $this->User->orderBy('name','ASC')->whereHas('roles', function(Builder $q) use($role) {
                    if($role){
                        $q->where('slug',$role);
                    }
                });
        if($request->get('name')) {
            $users->where(function($query) use ($request) {
                $query->orWhere('name','LIKE', "%".$request->get('name')."%")
                        ->orWhere('name','LIKE', "%".$request->get('name')."%");
            });
        }
        if($request->get('email')) {
            $users->where('email',$request->get('email'));
        } 
        return $users->sortable('id')->paginate(30);
    }

    public function sendPushNotification($filleable,$tokens) {
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


    public function getSuggessionDeals($request)
    {
        $searchTerm = $request->get('term');
        $deals = Products::where('status','active')->where('name', 'LIKE', '%'. $searchTerm. '%')->select('id','name','image')->get();
        $dealsData = array();
        if(count($deals) > 0){
            foreach ($deals as $list) {
                $data['id'] = $list->id;
                $data['value'] = $list->name;
                $data['label'] = '
                <a href="javascript:void(0);">
                <img src="'.$list->S3Url.'" width="50" height="50"/>
                <span>'.$list->name.'</span>
                </a>';
                array_push($dealsData, $data);
            }
        }else{
                $data['id'] = '';
                $data['value'] = 'No Deals Found';
                $data['label'] = '
                <a href="javascript:void(0);">
                <span>No Deals Found</span>
                </a>';
                array_push($dealsData, $data);
        }
        return $dealsData;
    }    

    public function getUsersLists($request)
    {
        $searchTerm = $request->get('q');
        $users = $this->User->select('id','name')->orderBy('name','ASC')->whereHas('roles', function(Builder $q) {
                     $q->where('slug','customer');
                });
        if($request->get('q')) {
            $users =$users->where(function($query) use ($request) {
                $query->orWhere('name','LIKE', "%".$request->get('q')."%")
                        ->orWhere('name','LIKE', "%".$request->get('q')."%");
            });
        }
        $users = $users->get();
        return $users;
    }
}