<?php

namespace App\Repositories\UserProfile;

use DB,Mail;
use config,File;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailTemplates\Entities\EmailTemplate;
use App\Models\User;
use Modules\Users\Entities\GuestUsers;
use Modules\Roles\Entities\Role;
use Modules\Login\Entities\MobileVerification;
use Modules\Login\Entities\OtpVerifications;
use Illuminate\Support\Facades\Storage;
use Modules\Products\Entities\Products;
use Modules\Advertisements\Entities\Advertisements;

class UserProfileRepository implements UserProfileRepositoryInterface {

    function __construct(User $User) {
       $this->User = $User;
    }

    public function getUserDetails($request)
    {
        $user = auth()->user();

        $user['user_image_path'] = $user->S3Url;
        $response['status_code'] = 200;
        $response['message'] = 'User Profile details';
        $response['data'] = $user;
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function userUpdate($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            //'phone' => ['required'],
        ]);
        if($validator->fails()){    
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $user = auth()->user();
        if($user){
            if($request->get('image')){
                $request['image'] = $request->get('image');
            }else{
                $request['image'] = auth()->user()->image;
            }
            $user->update($request->all());
            $user = User::find(auth()->user()->id);
            $user['user_image_path'] = $user->S3Url;
            $response['status_code'] = 200;
            $response['message'] = 'User updated successfully';
            $response['data'] = $user;
            return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        }else{
            $response['status_code'] = 400;
            $response['message'] = 'User not found';
            $response['data'] = $user;
            return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        }
    }

    public function userChangePassword($request)
    {
        $validator = Validator::make($request->all(), [
            'confirm_password' => 'required|same:new_password',
            'new_password' => 'required|min:8',
            'old_password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $user = auth()->user();
        if($user){
            try {
               if ((Hash::check(request('old_password'), auth()->user()->password)) == false) {
                  return response()->json([
                    'status_code' => 400,
                    'message' => 'Your old password is invalid',
                    'data' => []
                  ], 400);
               } else if ((Hash::check(request('new_password'), auth()->user()->password)) == true) {
                     return response()->json([
                       'status_code' => 400,
                       'message' => 'Please enter a password which is not similar then current password.',
                       'data' => []
                      ], 400);
               } else {
                   $user->update(['password' => Hash::make($request['new_password'])]);
                  return response()->json([
                    'status_code' => 200,
                    'message' => 'Password changed successfully',
                    'data' => []
                  ], 200);
               }
           } catch (\Exception $e) {
               return response()->json([
                    'status_code' => 400,
                    'message' => 'Bad request',
                    'data' => []
                ], 400);
           }

         }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'Not Found',
                'data' => auth()->uer()
            ], 404); 
        }
    }

    public function getSignedURL($request)
    {
        $location = 'images/user';
        $s = strtoupper(md5(uniqid(rand(),true))); 
        $guidText = 
            substr($s,0,8) . '-' . 
            substr($s,8,4) . '-' . 
            substr($s,12,4). '-' . 
            substr($s,16,4). '-' . 
            substr($s,20); 
        $prevFileName = $location.'/'.$guidText.'.jpg';
        $filename = $guidText.'.jpg';
        $prevFileUrl = \Storage::disk('s3')->url($prevFileName);
        $s3 = \Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+20 minutes";
        $bucketName = env("AWS_BUCKET");
        $command = $client->getCommand('putObject', [
            'Bucket' => $bucketName,
            'Key'    => $prevFileName,
            'ACL' => 'public-read',
            'ContentType' => 'image/jpeg'
        ]);

        $return = $client->createPresignedRequest($command, $expiry);
        if(isset($return)) {
            $signeUrl = (string)$return->getUri();
            $response = [
                'status_code' =>  200,
                'message' =>  'URL created successfully',
            ];
            $response['data']['url'] = $signeUrl;
            $response['data']['filename'] = $filename;
            $response['data']['preview_file_url'] = $prevFileUrl;
            return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        } else {
            return response()->json([
                'status_code' => 400, 
                'message' => 'URL not created', 
                'data'=> $dataArray
            ],400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
        }
    }

    public function getHomepageData($request)
    {
        if($request->headers->get('IsGguest')=='true'){
            $validator = Validator::make($request->all(), [
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        }
        $dealOfday = Products::where('status','active')->where('deal_of_the_day',1)->orderBy('id', 'DESC')->where('expiry_date', '>', utctodtc_4now())->limit(10)->get();
        $allDeals  = Products::where('status','active')->orderBy('id', 'DESC')->where('expiry_date', '>', utctodtc_4now())->paginate(10);
        $advertisment = Advertisements::where('status',1)->where('page','home')->orderBy('ad_order','asc')->limit(3)->get();
       
        $response = paginationFormat($allDeals);
        $response['status_code'] = 200;
        $response['message'] = 'Home page data';
        if (count($dealOfday)>0) {
            foreach ($dealOfday as $key => $list) {
                $response['data']['dealOfday'][$key] = $list;
                $response['data']['dealOfday'][$key]['product_image_url'] = $list->S3Url;
                $response['data']['dealOfday'][$key]['totalLikes'] = $list->likes();
                $response['data']['dealOfday'][$key]['totalDisLikes'] = $list->dislikes();
                if($request->headers->get('IsGguest')=='false'){
                    $response['data']['dealOfday'][$key]['isFavorite'] = $list->IsFavProduct;
                }else{
                    $response['data']['dealOfday'][$key]['isFavorite'] = $list->GuestIsFavProduct($list->id,$request->device_uid);
                }
                
                $response['data']['dealOfday'][$key]['isLike'] = $list->IsUserLike;
                $response['data']['dealOfday'][$key]['isDisLike'] = $list->IsUserDisLike;
            }
        } else {
            $response['data']['dealOfday'] = array();
        }

        if (count($allDeals)>0) {
            foreach ($allDeals as $key => $list) {
                $response['data']['allDeals'][$key] = $list;
                $response['data']['allDeals'][$key]['product_image_url'] = $list->S3Url;
                $response['data']['allDeals'][$key]['totalLikes'] = $list->likes();
                $response['data']['allDeals'][$key]['totalDisLikes'] = $list->dislikes();
                $response['data']['allDeals'][$key]['isFavorite'] = $list->IsFavProduct;
                $response['data']['allDeals'][$key]['isLike'] = $list->IsUserLike;
                $response['data']['allDeals'][$key]['isDisLike'] = $list->IsUserDisLike;
            }
        } else {
            $response['data']['allDeals'] = array();
        } 

        if (count($advertisment)>0) {
            foreach ($advertisment as $key => $list) {
                $response['data']['advertisment'][$key]['slug'] = $list->slug;
                $response['data']['advertisment'][$key]['page'] = $list->page;
                $response['data']['advertisment'][$key]['advertisement_link'] = ($list->advertisement_link) ? $list->advertisement_link : "";
                $response['data']['advertisment'][$key]['advertisement_image_url'] = $list->S3Url;
            }
        } else {
            $response['data']['advertisment'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function getAutocompleteData($request)
    {
        $products = array();
        if($request->get('query')){
            $products = Products::where('status','active')->where('expiry_date', '>', utctodtc_4now())->orderBy('id', 'DESC');
            $products = $products->where('name', 'LIKE', '%'.$request->get('query').'%');
            $products = $products->limit(15)->get();
        } 
       
        $response['status_code'] = 200;
        if (count($products)>0) {
            $response['message'] = 'Products data listing.';
            foreach ($products as $key => $list) {
                $response['data'][$key]['id'] = $list->id;
                $response['data'][$key]['slug'] = $list->slug;
                $response['data'][$key]['name'] = $list->name;
                $response['data'][$key]['product_image_url'] = $list->S3Url;
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'There is no record found.';
            $response['data'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function updateNotificationStatus($request)
    {
        if($request->headers->get('IsGguest')!='true'){
            $validator = Validator::make($request->all(), [
                'notification_status' => 'required|in:1,0',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $user = auth()->user();
            if($user){
                $user->update($request->all());
                $user = User::find(auth()->user()->id);
                $response['status_code'] = 200;
                $response['message'] = 'Notification status updated successfully';
                $response['data'] = $user;
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            }else{
                $response['status_code'] = 400;
                $response['message'] = 'User not found';
                $response['data'] = $user;
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'device_uid' => 'required|exists:guest_users,device_uid',
                'notification_status' => 'required|in:1,0',
            ]); 
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $user = GuestUsers::where('device_uid',$request->device_uid)->first();
            if($user){
                $user->update($request->all());
                $user = GuestUsers::find($user->id);
                $response['status_code'] = 200;
                $response['message'] = 'Notification status updated successfully';
                $response['data'] = $user;
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            }else{
                $response['status_code'] = 400;
                $response['message'] = 'User not found';
                $response['data'] = [];
                return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            }
        }
    }

    public function deleteAccountPermanently($request)
    {
        $user = auth()->user();
        if($user){
            try {
                  $user->delete();
                  return response()->json([
                    'status_code' => 200,
                    'message' => 'User Deleted successfully',
                    'data' => []
                  ], 200);
              
           } catch (\Exception $e) {
               return response()->json([
                    'status_code' => 400,
                    'message' => 'Bad request',
                    'data' => []
                ], 400);
           }

         }else{
            return response()->json([
                'status_code' => 404,
                'message' => 'User Not Found',
                'data' => auth()->uer()
            ], 404); 
        }
    }
}