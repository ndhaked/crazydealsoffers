<?php

namespace App\Repositories\Favorite;

use DB,Mail;
use config,File;
use Validator;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\Products\Entities\UserFavoriteProducts;
use Modules\Products\Entities\Products;
use App\Models\User;
use Modules\Advertisements\Entities\Advertisements;

class FavoriteRepository implements FavoriteRepositoryInterface {

    function __construct(UserFavoriteProducts $UserFavoriteProducts) {
       $this->UserFavoriteProducts = $UserFavoriteProducts;
    }

    public function addFavoriteUnfavroite($request)
    {
        if($request->headers->get('IsGguest') && $request->headers->get('IsGguest')=='true'){
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:fav,unfav',
                'product_id' => 'required|exists:products,id',
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message']= '';
            if($request->type=='fav'){
                $exists = $this->UserFavoriteProducts->where('device_uid',$request->device_uid)->where('product_id',$request->product_id)->delete();
                $this->UserFavoriteProducts->create(['device_uid'=>$request->device_uid,'product_id'=>$request->product_id]);
                $response['message'] = 'Product add to favorite successfully';
            }else{
                $this->UserFavoriteProducts->where('device_uid',$request->device_uid)->where('product_id',$request->product_id)->delete();
                 $response['message'] = 'Product unfavorite successfully';
            }
              return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        }else{  
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:fav,unfav',
                'product_id' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message']= '';
            if($request->type=='fav'){
                $exists = $this->UserFavoriteProducts->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->delete();
                $this->UserFavoriteProducts->create(['user_id'=>auth()->user()->id,'product_id'=>$request->product_id]);
                $response['message'] = 'Product add to favorite successfully';
            }else{
                $this->UserFavoriteProducts->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->delete();
                 $response['message'] = 'Product unfavorite successfully';
            }
              return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        } 
    }

    public function listFavoriteProduct($request)
    {
        if($request->headers->get('IsGguest')!='true'){
            $favoritList = UserFavoriteProducts::where('user_id',auth()->user()->id)->whereHas('products', function(Builder $q) {
                            $q->where('status','active');
                            $q->where('expiry_date', '>', utctodtc_4now());
                    })->paginate(10);
            $advertisment = Advertisements::where('status',1)->where('page','favorite')->inRandomOrder()->limit(1)->get();
            $response = paginationFormat($favoritList);
            $response['status_code'] = 200;
            $response['message'] = 'Favorite deals listing.';
            if (count($favoritList) > 0) {
                foreach ($favoritList as $key => $list) {
                    $response['data']['favorite'][$key] = $list->products;
                    $response['data']['favorite'][$key]['product_image_url'] = $list->products->S3Url;
                    $response['data']['favorite'][$key]['totalLikes'] = $list->products->likes();
                    $response['data']['favorite'][$key]['totalDisLikes'] = $list->products->dislikes();
                    $response['data']['favorite'][$key]['isFavorite'] = $list->products->IsFavProduct;
                    $response['data']['favorite'][$key]['isLike'] = $list->products->IsUserLike;
                    $response['data']['favorite'][$key]['isDisLike'] = $list->products->IsUserDisLike;
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'There is no record available.';
                $response['data']['favorite'] = array();
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
        }else{
            $validator = Validator::make($request->all(), [
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $favoritList = UserFavoriteProducts::where('device_uid',$request->device_uid)->whereHas('products', function(Builder $q) {
                            $q->where('status','active');
                            $q->where('expiry_date', '>', utctodtc_4now());
                    })->paginate(10);
            $advertisment = Advertisements::where('status',1)->where('page','favorite')->inRandomOrder()->limit(1)->get();
            $response = paginationFormat($favoritList);
            $response['status_code'] = 200;
            $response['message'] = 'Favorite deals listing.';
            if (count($favoritList) > 0) {
                foreach ($favoritList as $key => $list) {
                    $response['data']['favorite'][$key] = $list->products;
                    $response['data']['favorite'][$key]['product_image_url'] = $list->products->S3Url;
                    $response['data']['favorite'][$key]['totalLikes'] = $list->products->likes();
                    $response['data']['favorite'][$key]['totalDisLikes'] = $list->products->dislikes();
                    $response['data']['favorite'][$key]['isFavorite'] = true;
                   
                    $response['data']['favorite'][$key]['isLike'] = $list->products->IsUserLike;
                    $response['data']['favorite'][$key]['isDisLike'] = $list->products->IsUserDisLike;
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'There is no record available.';
                $response['data']['favorite'] = array();
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
    }
}