<?php

namespace App\Repositories\Blog;

use DB,Mail;
use config,File;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blogs\Entities\Blogs;

class BlogRepository implements BlogRepositoryInterface {

    function __construct(Blogs $Blogs) {
       $this->Blogs = $Blogs;
    }

    public function getBlogList($request)
    {
        $blogs = $this->Blogs->where('status', 1)->orderBy('id', 'DESC')->paginate(10);
        $response = paginationFormat($blogs);
        $response['status_code'] = 200;
        $response['message'] = 'Blogs listing.';
        if (count($blogs) > 0) {
            foreach ($blogs as $key => $list) {
                $response['data'][$key] = $list;
                $response['data'][$key]['blog_image_url1'] = $list->S3Url;
                $response['data'][$key]['blog_image_url2'] = $list->S3UrlImage2;
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'There is no record available.';
            $response['data'] = array();
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function getBlogDetail($request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if (Blogs::where('slug', $request->slug)->exists()) {
            $response = Blogs::where('slug', $request->slug)->first();
            $response['blog_image_url1'] = $response->S3Url;
            $response['blog_image_url2'] = $response->S3UrlImage2;
            $response['video_link'] = ($response->video_link) ? $response->video_link : "";
            $response['description'] = ($response->description) ? $response->description : "";
            return response()->json([
                'status_code' => 200,
                'message' => 'Blog Detail',
                'data' => $response
            ], 200)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(200);
        }else{
            return response()->json([
                'status_code' => 400,
                'message' => 'Record Not Found',
                'data' => []
            ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
        }
    }

}