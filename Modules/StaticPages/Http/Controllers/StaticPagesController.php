<?php

namespace Modules\StaticPages\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\StaticPages\Http\Requests\CreateStaticPagesRequest;
use Modules\StaticPages\Http\Requests\UpdateStaticPagesRequest;
use Modules\StaticPages\Http\Requests\BannerImageMediaRequest;
use Modules\StaticPages\Repositories\StaticPagesRepositoryInterface as StaticPagesRepo;

class StaticPagesController extends Controller
{
    
    public function __construct(StaticPagesRepo $StaticPagesRepo)
    {
        $this->middleware(['ability','auth']);
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
            return $this->StaticPagesRepo->getAjaxData($request);
        }
        return view('staticpages::index')->withModel('staticpages');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('staticpages::create')->withModel('staticpages');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateStaticPagesRequest $request)
    {
        $response = $this->StaticPagesRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('staticpages.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$slug)
    {
        $data =  $this->StaticPagesRepo->getRecordBySlug($slug);
        if($data){
          return view('staticpages::edit',compact('data'))->withModel('staticpages');  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('staticpages.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateStaticPagesRequest $request, $id)
    {
        $data =  $this->StaticPagesRepo->getRecord($id);
        if($data){
            $response = $this->StaticPagesRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('staticpages.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('staticpages.index');
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->StaticPagesRepo->getRecord($id);
            if($data){
                $this->StaticPagesRepo->destroy($id);
                Session::flash('success', trans('flash.success.static_page_deleted_successfully'));
                return redirect()->route('staticpages.index');
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('staticpages.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('staticpages.index');
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(BannerImageMediaRequest $request)
    { 
        try {
            $response = $this->StaticPagesRepo->saveBannerImageMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
