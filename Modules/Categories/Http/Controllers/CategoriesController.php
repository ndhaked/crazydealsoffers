<?php

namespace Modules\Categories\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Categories\Http\Requests\CreateCategoriesRequest;
use Modules\Categories\Http\Requests\UpdateCategoriesRequest;
use Modules\Categories\Http\Requests\CategoryMediaRequest;
use Modules\Categories\Repositories\CategoriesRepositoryInterface as CatRepo;

class CategoriesController extends Controller
{
    protected $model = 'Categories';
    public function __construct(CatRepo $CatRepo)
    {
        $this->middleware(['ability','auth']);
        $this->CatRepo = $CatRepo;
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(request()->ajax())
        {
            return $this->CatRepo->getAjaxData($request);
        }
        return view('categories::index')->withModel(strtolower($this->model));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $categories = $this->CatRepo->getAllParentCategories();
        return view('categories::create', compact('categories'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateCategoriesRequest $request)
    {
        $response = $this->CatRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('categories.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('categories::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->CatRepo->getRecordBySlug($slug);
        if($data){
          $categories = $this->CatRepo->getAllParentCategories();
          return view('categories::edit',compact('data','categories'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('categories.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateCategoriesRequest $request, $id)
    {
        $data =  $this->CatRepo->getRecord($id);
        if($data){
            $response = $this->CatRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('categories.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->CatRepo->getRecordBySlug($slug);
            if($data){
                $this->CatRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.category_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('categories.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('categories.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('categories.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request,$slug)
    {   
        $response = $this->CatRepo->changeStatus($request,$slug);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back();   
    }

     /**
     * upload Category picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(CategoryMediaRequest $request) {
        try {
            $response = $this->CatRepo->saveCategoryPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
