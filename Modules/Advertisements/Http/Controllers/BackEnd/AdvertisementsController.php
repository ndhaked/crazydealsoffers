<?php

namespace Modules\Advertisements\Http\Controllers\BackEnd;

use Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Advertisements\Http\Requests\CreateAdvertisementRequest;
use Modules\Advertisements\Http\Requests\AdvertisementMediaRequest;
use Modules\Advertisements\Http\Requests\UpdateAdvertisementRequest;
use Modules\Advertisements\Repositories\AdvertisementsRepositoryInterface as AdvertisementRepo;

class AdvertisementsController extends Controller
{
    protected $model = 'Advertisements';
    public function __construct(AdvertisementRepo $AdvertisementRepo)
    {
        $this->middleware(['ability','auth']);
        $this->AdvertisementRepo = $AdvertisementRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return view('advertisements::index');
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->AdvertisementRepo->getAjaxData($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('advertisements::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateAdvertisementRequest $request)
    {
        $response = $this->AdvertisementRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('advertisement.index');
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
        $advertisements =  $this->AdvertisementRepo->getRecordBySlug($id);
        if($advertisements){
          return view('advertisements::edit',compact('advertisements'));  
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
        $response = $this->AdvertisementRepo->changeStatus($request,$id);
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
    public function update(UpdateAdvertisementRequest $request, $id)
    {
        $data =  $this->AdvertisementRepo->getRecord($id);
        if($data){
            $response = $this->AdvertisementRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('advertisement.index');
        }
        Session::flash('error', trans('flash.error.record_not_available_now'));
        return redirect()->route('advertisement.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $data =  $this->AdvertisementRepo->getRecord($id);
            if($data){
                $this->AdvertisementRepo->destroy($id);
                Session::flash('success', trans('flash.success.blog_deleted_successfully'));
                return redirect()->route('advertisement.index');
            }
            Session::flash('error', trans('flash.error.record_not_available_now'));
            return redirect()->route('advertisement.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_record_try_later'));
            return redirect()->route('advertisement.index');
        }
    }

    /**
     * upload Category picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(AdvertisementMediaRequest $request) {
        try {
            $response = $this->AdvertisementRepo->saveProductPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
