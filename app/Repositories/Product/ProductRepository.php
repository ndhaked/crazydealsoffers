<?php

namespace App\Repositories\Product;

use DB, Mail;
use config, File;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\Products\Entities\Products;
use Modules\Products\Entities\ProductComment;
use Modules\Categories\Entities\Categories;
use Modules\Advertisements\Entities\Advertisements;

class ProductRepository implements ProductRepositoryInterface
{

    function __construct(Products $Products)
    {
        $this->Products = $Products;
    }

    public function getProductList($request)
    {
        if ($request->headers->get('IsGguest') == 'true') {
            $validator = Validator::make($request->all(), [
                'device_uid' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        }
        $products = Products::where('status', 'active')->orderBy('id', 'DESC')->where('expiry_date', '>', utctodtc_4now());

        if ($request->get('query')) {
            $products = $products->where('name', 'LIKE', '%' . $request->get('query') . '%');
        }
        if ($request->get('category_id')) {
            $products = $products->where('category_id', $request->get('category_id'));
        }
        if ($request->get('product_id')) {
            $products = $products->where('id', $request->get('product_id'));
        }
        $products = $products->paginate(10);
        $response = paginationFormat($products);
        $response['status_code'] = 200;
        $response['message'] = 'Products data listing.';
        if (count($products) > 0) {
            foreach ($products as $key => $list) {
                $response['data'][$key] = $list;
                $response['data'][$key]['PublisherName'] = $list->PublisherName;
                $response['data'][$key]['PublisherImage'] = $list->PublisherImage;
                $response['data'][$key]['product_image_url'] = $list->S3Url;
                $response['data'][$key]['totalLikes'] = $list->likes();
                $response['data'][$key]['totalDisLikes'] = $list->dislikes();
                if ($request->headers->get('IsGguest') == 'false') {
                    $response['data'][$key]['isFavorite'] = $list->IsFavProduct;
                } else {
                    $response['data'][$key]['isFavorite'] = $list->GuestIsFavProduct($list->id, $request->device_uid);
                }
                $response['data'][$key]['isLike'] = $list->IsUserLike;
                $response['data'][$key]['isDisLike'] = $list->IsUserDisLike;
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'There is no record found.';
            $response['data'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function getProductDetail($request)
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

        if (Products::where('slug', $request->slug)->where('expiry_date', '>', utctodtc_4now())->exists()) {
            $response = Products::where('slug', $request->slug)->first();
            $response['price'] = fixPriceFormate($response->price);
            $response['expiry_date_custom'] = $response->expiry_date->format('j F, Y - h:i A');
            $response['created_date_custom'] = $response->created_at->format('j F, Y - h:i A');
            $response['product_image_url'] = $response->S3Url;
            $response['PublisherName'] = $response->PublisherName;
            $response['PublisherImage'] = $response->PublisherImage;
            $response['totalLikes'] = $response->likes();
            $response['totalDisLikes'] = $response->dislikes();
            $response['totalComments'] = $response->comments()->count();
            if ($request->headers->get('IsGguest') == 'false') {
                $response['isFavorite'] = $response->IsFavProduct;
            } else {
                $response['isFavorite'] = $response->GuestIsFavProduct($response->id, $request->device_uid);
            }
            if ($request->headers->get('IsGguest') == 'false') {
                $response['isComment'] = $response->IsCommentProduct;
            } else {
                $response['isComment'] = $response->GuestIsCommentProduct($response->id, $request->device_uid);
            }
            $response['isLike'] = $response->IsUserLike;
            $response['isDisLike'] = $response->IsUserDisLike;
            $response['category_name'] = ($response->category) ? $response->category->name : "";
            $commentsLatestTwo = ProductComment::where('product_id', $response->id)->whereNull('parent_id')->whereNotNull('user_id')->orderBy('id', 'desc')->limit(2)->get();
            if (count($commentsLatestTwo) > 0) {
                foreach ($commentsLatestTwo as $ckey => $comment) {
                    $comments[$ckey]['id'] = $comment->id;
                    $comments[$ckey]['product_id'] = $comment->product_id;
                    $comments[$ckey]['comment'] = $comment->comment;
                    $comments[$ckey]['username'] = $comment->user->name;
                    $comments[$ckey]['user_pic'] = $comment->user->PicturePath;
                    $comments[$ckey]['IsAdminWithRole'] = $comment->IsAdminWithRole;
                    $comments[$ckey]['created_view_date'] = $comment->created_at->diffForHumans();
                    if ($request->headers->get('IsGguest') == 'false') {
                        $comments[$ckey]['isLike'] = $comment->IsUserLike;
                    } else {
                        $comments[$ckey]['isLike'] = $comment->GuestIsLikeOnComment($comment->id, $request->device_uid);
                    }
                    if ($request->headers->get('IsGguest') == 'false') {
                        $comments[$ckey]['isDisLike'] = $comment->IsUserDisLike;
                    } else {
                        $comments[$ckey]['isDisLike'] = $comment->GuestIsDisLikeOnComment($comment->id, $request->device_uid);
                    }
                    $comments[$ckey]['totalLikes'] = $comment->likes();
                    $comments[$ckey]['totalDisLikes'] = $comment->dislikes();
                    $comments[$ckey]['totalReplies'] = $comment->replies()->count();
                    if ($request->headers->get('IsGguest') == 'false') {
                        $comments[$ckey]['isReplyOnComment'] = $comment->IsReplyOnComment;
                    } else {
                        $comments[$ckey]['isReplyOnComment'] = $comment->GuestIsReplyOnComment($comment->id, $request->device_uid);
                    }
                    if (count($comment->replies) > 0) {
                        foreach ($comment->replies as $rkey => $repcomment) {
                            $comments[$ckey]['replies']['comments'][$rkey]['IsAdminWithRole'] = $repcomment->IsAdminWithRole;;
                            $comments[$ckey]['replies']['comments'][$rkey]['comment'] = $repcomment->comment;
                            $comments[$ckey]['replies']['comments'][$rkey]['created_at'] = $repcomment->created_at;
                            $comments[$ckey]['replies']['comments'][$rkey]['created_view_date'] = $repcomment->created_at->diffForHumans();
                            $comments[$ckey]['replies']['comments'][$rkey]['id'] = $repcomment->id;
                            $comments[$ckey]['replies']['comments'][$rkey]['parent_id'] = $repcomment->parent_id;
                            $comments[$ckey]['replies']['comments'][$rkey]['product_id'] = $repcomment->product_id;
                            $comments[$ckey]['replies']['comments'][$rkey]['user_pic'] = ($repcomment->user) ? $repcomment->user->PicturePath : onerrorReturnImage();
                            $comments[$ckey]['replies']['comments'][$rkey]['username'] = ($repcomment->user) ? $repcomment->user->name : 'Guest User';
                        }
                    } else {
                        $comments[$ckey]['replies'] = [];
                    }
                }
            } else {
                $comments = [];
            }
            $response['comments'] = $comments;
            return response()->json([
                'status_code' => 200,
                'message' => 'Get Product Detail',
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

    public function getDealOffTheDayList($request)
    {
        $products = Products::where('status', 'active')->where('deal_of_the_day', 1)->orderBy('id', 'DESC')->where('expiry_date', '>', utctodtc_4now());
        if ($request->get('name')) {
            $products = $products->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }
        if ($request->get('coupon_code')) {
            $products = $products->where('coupon_code', 'LIKE', '%' . $request->get('coupon_code') . '%');
        }
        $products = $products->limit(10)->get();
        $response['status_code'] = 200;
        $response['message'] = 'Deal of the day listing.';
        if (count($products) > 0) {
            foreach ($products as $key => $list) {
                $response['data'][$key] = $list;
                $response['data'][$key]['product_image_url'] = $list->S3Url;
                $response['data'][$key]['totalLikes'] = $list->likes();
                $response['data'][$key]['totalDisLikes'] = $list->dislikes();
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'There is no record found.';
            $response['data'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function getCategoryList($request)
    {
        $categories = Categories::select('id', 'slug', 'name', 'description', 'image')->where('status', 1)->get();
        $advertisment = Advertisements::where('status', 1)->where('page', 'category')->inRandomOrder()->limit(1)->get();
        $response['status_code'] = 200;
        $response['message'] = 'Categories listing.';
        if (count($categories) > 0) {
            foreach ($categories as $key => $list) {
                $response['data']['categories'][$key] = $list;
                $response['data']['categories'][$key]['image_url'] = $list->S3Url;
                $response['data']['categories'][$key]['description'] = ($list->description) ? $list->description : "";
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'There is no record found.';
            $response['data'] = array();
        }

        if (count($advertisment) > 0) {
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

    public function setCronmakeStatusExpiredDeals()
    {

        $products = Products::where('status', 'active')->where('expiry_date', '<', utctodtc_4now())->get();
        if (count($products) > 0) {
            foreach ($products as $deal) {
                $deal->status = 'expired';
                $deal->save();
            }
        }

        $response['status_code'] = 200;
        $response['message'] = 'Expired Successfully';
        return response()->json($response);
    }
}
