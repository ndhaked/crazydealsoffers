<?php

namespace App\Repositories\LikeDislike;

use DB,Mail;
use config,File;
use Validator;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\Products\Entities\LikeDislikes;
use Modules\Products\Entities\Products;
use App\Models\User;

class LikeDislikeRepository implements LikeDislikeRepositoryInterface {

    function __construct(LikeDislikes $LikeDislikes,Products $Products) {
       $this->LikeDislikes = $LikeDislikes;
       $this->Products = $Products;
    }

    public function addLikeDislike($request)
    {
        if($request->headers->get('IsGguest') && $request->headers->get('IsGguest')=='true'){
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:like,dislike',
                'type_value' => 'required|in:0,1',
                'product_id' => 'required|exists:products,id',
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message']= '';
            if (Products::where('id', $request->product_id)->exists()) {
                if($request->type=='like'){
                    $exists = $this->LikeDislikes->where('device_uid',$request->device_uid)->where('product_id',$request->product_id)->delete();
                    if($request->type_value ==1){
                        $this->LikeDislikes->create(['device_uid'=>$request->device_uid,'product_id'=>$request->product_id,'like'=>1]);
                        $product = $this->Products->find($request->product_id);
                        $response['message'] = 'Product like successfully';
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }else{
                        $product = $this->Products->find($request->product_id);
                        $response['message'] = 'Product unlike successfully';
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }
                }else{
                    $exists = $this->LikeDislikes->where('device_uid',$request->device_uid)->where('product_id',$request->product_id)->delete();
                    if($request->type_value ==1){
                        $this->LikeDislikes->create(['device_uid'=>$request->device_uid,'product_id'=>$request->product_id,'dislike'=>1]);
                        $response['message'] = 'Product dislike successfully';
                        $product = $this->Products->find($request->product_id);
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }else{
                        $response['message'] = 'Product undislike successfully';
                        $product = $this->Products->find($request->product_id);
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }
                }
                  return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Record Not Found',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:like,dislike',
                'type_value' => 'required|in:0,1',
                'product_id' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $response['status_code'] = 200;
            $response['message']= '';
            if (Products::where('id', $request->product_id)->exists()) {
                if($request->type=='like'){
                    $exists = $this->LikeDislikes->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->delete();
                    if($request->type_value ==1){
                        $this->LikeDislikes->create(['user_id'=>auth()->user()->id,'product_id'=>$request->product_id,'like'=>1]);
                        $product = $this->Products->find($request->product_id);
                        $response['message'] = 'Product like successfully';
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }else{
                        $product = $this->Products->find($request->product_id);
                        $response['message'] = 'Product unlike successfully';
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }
                }else{
                    $exists = $this->LikeDislikes->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->delete();
                    if($request->type_value ==1){
                        $this->LikeDislikes->create(['user_id'=>auth()->user()->id,'product_id'=>$request->product_id,'dislike'=>1]);
                        $response['message'] = 'Product dislike successfully';
                        $product = $this->Products->find($request->product_id);
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }else{
                        $response['message'] = 'Product undislike successfully';
                        $product = $this->Products->find($request->product_id);
                        $response['data']['totalLikes'] = $product->likes();
                        $response['data']['totalDisLikes'] = $product->dislikes();
                    }
                }
                  return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Record Not Found',
                    'data' => []
                ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
            }
        }
    }
}