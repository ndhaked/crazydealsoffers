<?php

namespace Modules\Slider\Http\Controllers\BackEnd;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Slider\Http\Requests\CreateSliderRequest;
use Modules\Slider\Http\Requests\UpdateSliderRequest;
use Modules\Slider\Http\Requests\SliderMediaRequest;
use Modules\Slider\Repositories\SliderRepositoryInterface as SliderRepo;

class SliderController extends Controller
{
    protected $model = 'Slider';

    public function __construct(SliderRepo $SliderRepo)
    {
        $this->middleware(['auth','ability'])->except('getAjaxData');
        $this->SliderRepo = $SliderRepo;

    }
    
        /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $sliderLimit =  $this->SliderRepo->getSliderCount();
        return view('slider::index',compact('sliderLimit'))->withModel(strtolower($this->model));
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->SliderRepo->getAjaxData($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $sliderLimit =  $this->SliderRepo->getSliderCount();
        $sliderOrder =  $this->SliderRepo->getSliderOrder();

        if($sliderLimit >= 5){
            Session::flash('error', trans('flash.error.slider_limitation'));
            return redirect()->route('slider.index');
        }
        return view('slider::create',compact('sliderOrder'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateSliderRequest $request)
    {
        $response = $this->SliderRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('slider.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $slider =  $this->SliderRepo->getRecordBySlug($slug);
        $sliderOrder =  $this->SliderRepo->getEditSliderOrder($slider->slider_order);

        if($slider){
          return view('slider::edit',compact('slider','sliderOrder'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('slider.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateSliderRequest $request, $id)
    {
        $data =  $this->SliderRepo->getRecord($id);
        if($data){
            $response = $this->SliderRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('slider.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('slider.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->SliderRepo->getRecordBySlug($slug);
            if($data){
                $this->SliderRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.slider_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('slider.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('slider.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('slider.index');
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
        $response = $this->SliderRepo->changeStatus($request,$slug);
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
    public function saveMedia(SliderMediaRequest $request) {
        try {
            $response = $this->SliderRepo->saveSliderPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function showHide(Request $request)
    {
        $response = $this->SliderRepo->sliderShowHide($request->status);
        return response()->json($response);
    }
}
