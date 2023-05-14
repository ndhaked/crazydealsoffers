<?php

namespace App\Http\Controllers\Api\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Blog\BlogRepositoryInterface as BlogListRepo;

class BlogController extends BaseController
{
    /**
     * Create a Blog Controller instance.
     *
     * @return void
     */
    public function __construct(BlogListRepo $BlogRepo,Request $request) {
        $this->BlogRepo = $BlogRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    /**
     * Get the Blog List.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogList(Request $request) {
        $response = $this->BlogRepo->getBlogList($request);
        return $response;
    }

    /**
     * Get the Blog Detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogDetail(Request $request) {
        $response = $this->BlogRepo->getBlogDetail($request);
        return $response;
    }
}