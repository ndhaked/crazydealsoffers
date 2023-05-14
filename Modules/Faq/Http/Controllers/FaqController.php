<?php

namespace Modules\Faq\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Faq\Http\Requests\CreateFaqRequest;
use Modules\Faq\Http\Requests\UpdateFaqRequest;
use Modules\Faq\Repositories\FaqRepositoryInterface as FaqRepo;
use Modules\StaticPages\Repositories\StaticPagesRepositoryInterface as StaticPagesRepo;

class FaqController extends Controller
{
    public function __construct(FaqRepo $FaqRepo,StaticPagesRepo $StaticPagesRepo)
    {
        $this->middleware(['ability','auth'],['except' => ['faq']]);
        $this->FaqRepo = $FaqRepo;
        $this->StaticPagesRepo = $StaticPagesRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(request()->ajax()) 
        {
            return $this->FaqRepo->getAjaxData($request);
        }
        return view('faq::index')->withModel('faq');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('faq::create')->withModel('faq');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateFaqRequest $request)
    {
        $response = $this->FaqRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('faq.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$slug)
    {
        $data =  $this->FaqRepo->getRecordBySlug($slug);
        if($data){
          return view('faq::edit',compact('data'))->withModel('faq');  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('faq.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateFaqRequest $request, $id)
    {
        $data =  $this->FaqRepo->getRecord($id);
        if($data){
            $response = $this->FaqRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('faq.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('faq.index');
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->FaqRepo->getRecordBySlug($slug);
            if($data){
                $this->FaqRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.faq_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('faq.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('faq.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('faq.index');
        }
    }
}