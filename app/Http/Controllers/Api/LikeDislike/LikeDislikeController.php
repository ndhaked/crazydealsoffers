<?php

namespace App\Http\Controllers\Api\LikeDislike;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\LikeDislike\LikeDislikeRepositoryInterface as LikeDislikeRepo;

class LikeDislikeController extends BaseController
{
    /**
     * Create a LikeDislike Controller instance.
     *
     * @return void
     */
    public function __construct(LikeDislikeRepo $LikeDislikeRepo,Request $request) {
        $this->LikeDislikeRepo = $LikeDislikeRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    /**
     * Add the LikeDislike.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLikeDislike(Request $request) {
        $response = $this->LikeDislikeRepo->addLikeDislike($request);
        return $response;
    }
}