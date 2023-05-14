<?php

namespace Modules\Blogs\Http\Controllers\BackEnd;

use Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blogs\Http\Requests\CreateBlogRequest;
use Modules\Blogs\Http\Requests\BlogMediaRequest;
use Modules\Blogs\Http\Requests\UpdateBlogRequest;
use Modules\Blogs\Repositories\BlogsRepositoryInterface as BlogRepo;

class BlogsController extends Controller
{
    protected $model = 'Blogs';
    public function __construct(BlogRepo $BlogRepo)
    {
        $this->middleware(['ability','auth']);
        $this->BlogRepo = $BlogRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return view('blogs::index');
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->BlogRepo->getAjaxData($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('blogs::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateBlogRequest $request)
    {
        $response = $this->BlogRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('blog.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('products::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $blogs =  $this->BlogRepo->getRecordBySlug($id);
        if($blogs){
          return view('blogs::edit',compact('blogs'));  
        }
        Session::flash('error', trans('flash.error.record_not_available_now'));
        return redirect()->route('blog.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request,$id)
    {   
        $response = $this->BlogRepo->changeStatus($request,$id);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back();   
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateBlogRequest $request, $id)
    {
        $data =  $this->BlogRepo->getRecord($id);
        if($data){
            $response = $this->BlogRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('blog.index');
        }
        Session::flash('error', trans('flash.error.record_not_available_now'));
        return redirect()->route('blog.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $data =  $this->BlogRepo->getRecord($id);
            if($data){
                $this->BlogRepo->destroy($id);
                Session::flash('success', trans('flash.success.blog_deleted_successfully'));
                return redirect()->route('blog.index');
            }
            Session::flash('error', trans('flash.error.record_not_available_now'));
            return redirect()->route('blog.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_record_try_later'));
            return redirect()->route('blog.index');
        }
    }

    /**
     * upload Category picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(BlogMediaRequest $request) {
        try {
            $response = $this->BlogRepo->saveProductPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }


    /**
     * Removing the second image.
     * @param int $id
     * @return Renderable
     */
    public function removeImage($id)
    {
        $response =  $this->BlogRepo->removeImage($id);
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('blog.edit',$id);
    }
}
