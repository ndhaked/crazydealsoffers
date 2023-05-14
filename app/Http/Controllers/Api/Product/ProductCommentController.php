<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Product\Comment\ProductCommentRepositoryInterface as ProductCommentRepo;

class ProductCommentController extends BaseController
{
    /**
     * Create a Product Comment Controller instance.
     *
     * @return void
     */
    public function __construct(ProductCommentRepo $ProductCommentRepo,Request $request) {
        $this->ProductCommentRepo = $ProductCommentRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api')->except('getUsersListForTag');
        return auth()->shouldUse('api');
    }

    /**
     *  add Comment.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(Request $request) {
        $response = $this->ProductCommentRepo->addComment($request);
        return $response;
    }

    public function getProductComments(Request $request) {
        $response = $this->ProductCommentRepo->getProductComments($request);
        return $response;
    }

    public function getUsersListForTag(Request $request) {
        $response = $this->ProductCommentRepo->getUsersListForTag($request);
        return $response;
    }

    /**
     * Add the CommentLikeDislike.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCommentLikeDislike(Request $request) {
        $response = $this->ProductCommentRepo->addCommentLikeDislike($request);
        return $response;
    }

    /**
     *  add Comment Reply.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCommentReply(Request $request) {
        $response = $this->ProductCommentRepo->addCommentReply($request);
        return $response;
    }

    public function getCommentsReply(Request $request) {
        $response = $this->ProductCommentRepo->getCommentsReply($request);
        return $response;
    }
}