<?php

namespace Modules\Products\Repositories;

use Modules\Products\Entities\Products;
use Modules\Categories\Entities\Categories;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Modules\Products\Repositories\ProductsRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllProductsExport;
use Illuminate\Support\Facades\Storage;
use App\Models\PushNotifications;
use Illuminate\Support\Facades\Http;
use WaleedAhmad\Pinterest\Facade\Pinterest;


class ProductsRepository implements ProductsRepositoryInterface {

    public $Products;
    protected $model = 'Products';

    function __construct(Products $Products,PushNotifications $PushNotifications) {
        $this->Products = $Products;
        $this->PushNotifications = $PushNotifications;
    }

    public function getAll($request)
    {
        $products = Products::orderBy('id','desc');

        if($request->get('name')) {
            $products = $products->where('name','LIKE','%'.$request->get('name').'%');
        }

        if($request->get('coupon_code')) {
            $products = $products->where('coupon_code','LIKE','%'.$request->get('coupon_code').'%');
        }

        if($request->get('type') == 'dealofday'){
            $products = $products->where('deal_of_the_day',1);
        }

        return $products->paginate();

    }

    public function getCategories(){
        return Categories::where('status',1)->pluck('name', 'id')->toArray();
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','category_id','name','coupon_code','price','off_on_product','expiry_date','item_purchase_link','description','delete_status','tag','user_id');
            if($request->get('image')){
                $filleable['image'] = $request->get('image');
            }
            if($request->get('tag')=='N/A'){
                $filleable['tag'] = NULL;
            }
            $filleable['user_id'] = auth()->user()->id;
            $this->Products->fill($filleable);
            $product = $this->Products->save();
            $image = $this->Products->S3Url;
            $description = $this->Products->description;
            //---------------------facebook post----------------------------//
                // if($request->facebook_1)
                // {
                //     $description = (!($this->Products->same_fb_1))?$this->Products->facebook_description_1:$description;
                //     $apiURL = 'https://graph.facebook.com/'.env('FACEBOOK_GROUP_1_PAGE_ID').'/feed';
                    
                //     $postInput = [
                //         'message' => $description,
                //         'photos' =>  $image,
                //         'link' => '',
                //         'access_token' => env('FACEBOOK_GROUP_1_TOKEN')
                //     ];
                //     $headers = [
                //         'X-header' => 'value'
                //     ];
                //     $response_group_01 = Http::post($apiURL, $postInput);
                //     $statusCode = $response_group_01->status();
                //     $responseBody = json_decode($response_group_01->getBody(), true);
                //     //dd($responseBody);
                // }
                // if($request->facebook_2)
                // {
                //     $description = (!($this->Products->same_fb_2))?$this->Products->facebook_description_2:$description;

                //     $apiURL = 'https://graph.facebook.com/'.env('FACEBOOK_GROUP_2_PAGE_ID').'/feed';
                //     $postInput = [
                //         'message' => $description,
                //         'photos' =>  $image,
                //         'access_token' => env('FACEBOOK_GROUP_2_TOKEN')
                //     ];
                //     $headers = [
                //         'X-header' => 'value'
                //     ];
                //     $response_group_02 = Http::post($apiURL, $postInput);
                //     $statusCode = $response_group_02->status();
                //     $responseBody = json_decode($response_group_02->getBody(), true);
                //     // dd($responseBody);
                // }
            //---------------------facebook post----------------------------//

            //Send Notification On product add
            $filleable['title'] = 'New Product added';
            $filleable['body']   = 'New Product added!! Check this Deal';
            $filleable['id']   = \DB::getPdo()->lastInsertId();
            //$this->PushNotifications->sendPushNotificationForAllUsers($filleable);
            $response['message'] = trans('flash.success.product_created_successfully');
            $response['id'] =\DB::getPdo()->lastInsertId();
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }   
         return $response;  
    }

    public function saveProductPictureMedia($request)
    {
        if($request->hasfile('files'))
        {
            $file = $request->file('files');
            $filename=time().'.'.$file->getClientOriginalExtension();
            $filePath = 'images/products/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }

    public function changeStatus($request,$id)
    {
            $change = $this->Products->find($id);
            $active = $change->status;
            if ($change != null) 
            {
                if($active=='active')
                {
                    $update_arr = array('status' => 'expired');
                    $this->Products->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 'active');
                    $this->Products->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.product_status_updated_successfully');
                 $type = 'success';
            }else{
                 $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                 $type = 'warning';
            }
        
         $response['status_code'] = 200;
         $response['message'] = $message;
         $response['type'] = $type;
         return $response;
    }

    public function getRecordBySlug($id)
    {
      return $this->Products->find($id);
    }

    public function getRecord($id)
    {
      return $this->Products->find($id);
    }
    
    public function update($request,$id)
    {
        try {
            $filleable = $request->only('slug','category_id','name','coupon_code','price','off_on_product','expiry_date','item_purchase_link','description','delete_status','tag');
            if($request->get('image')){
                $filleable['image'] = $request->get('image');
            }
            if($request->get('tag')=='N/A'){
                $filleable['tag'] = NULL;
            }
            $record = $this->getRecord($id);
            $coupancode = $record->coupon_code;
            $item_purchase_link = $record->item_purchase_link;
            $record->fill($filleable);
            $update =$record->save();
            $updatedRecord = $this->getRecord($id);
            
            if($updatedRecord->expiry_date > utctodtc_4now()){ 
                $updatedRecord->status = 'active';
                $updatedRecord->save();
                //Send Notification On product add
            }
            $body = '';
            if($coupancode != $filleable['coupon_code'] && $item_purchase_link != $filleable['item_purchase_link']){
                $body = 'Coupan code and Item purchased link is updated!! Check this deal';
            }elseif($coupancode != $filleable['coupon_code']){
                $body = 'Coupan code is updated!! Check this deal';
            }elseif($item_purchase_link != $filleable['item_purchase_link']){
               $body = 'Item purchased link is updated!! Check this deal';
            }
            if($body){
                $filleable['title']  = 'Product Updated';
                $filleable['body']   = $body;
                $filleable['id']     = $id;
                //$this->PushNotifications->sendPushNotificationForAllUsers($filleable);
            }
            $response['message'] = trans('flash.success.product_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->Products->destroy($id);
    }

    public function exportCSV()
    {
        return (new AllProductsExport())->download('products.csv');
    }

    public function dealFTheDayStatus($request,$id,$status)
    {
        $allDeals = $this->Products->where('status', 'active')->where('deal_of_the_day', 1)->pluck('deal_of_the_day');
        if(count($allDeals) >= 10 && $status == 1){
                $message =  'You cannot set more then 10 deal of the day products';
                $type = 'warning';
        }else{
            $change = $this->Products->find($id);
            $active = $change->deal_of_the_day;
            if ($change != null) 
            {
                if($active==1)
                {
                    $update_arr = array('deal_of_the_day' => 0);
                    $this->Products->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('deal_of_the_day' => 1);
                    $this->Products->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.product_deal_status_updated_successfully');
                 $type = 'success';
            }else{
                 $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                 $type = 'warning';
            }
        }
         $response['status_code'] = 200;
         $response['message'] = $message;
         $response['type'] = $type;
         return $response;
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

    public function getRemoveInactive()
    {
        return $this->Products->where('status','expired')->delete();
    }
}
