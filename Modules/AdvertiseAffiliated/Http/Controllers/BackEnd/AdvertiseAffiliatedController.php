<?php

namespace Modules\AdvertiseAffiliated\Http\Controllers\BackEnd;
use Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AdvertiseAffiliated\Http\Requests\AdvertiseAffiliatedMediaRequest;
use Modules\AdvertiseAffiliated\Http\Requests\UpdateAdvertiseAffiliatedRequest;
use Modules\AdvertiseAffiliated\Repositories\AdvertiseAffiliatedRepositoryInterface as AdvertiseAffiliatedRepo;

class AdvertiseAffiliatedController extends Controller
{

    protected $model = 'AdvertiseAffiliated';
    public function __construct(AdvertiseAffiliatedRepo $AdvertiseAffiliatedRepo)
    {
        $this->middleware(['ability','auth']);
        $this->AdvertiseAffiliatedRepo = $AdvertiseAffiliatedRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('advertiseaffiliated::index');
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->AdvertiseAffiliatedRepo->getAjaxData($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('advertiseaffiliated::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('advertiseaffiliated::show');
    }

    
    public function edit($slug)
    {
        $advertiseaffiliated =  $this->AdvertiseAffiliatedRepo->getRecordBySlug($slug);
        if($advertiseaffiliated){
          return view('advertiseaffiliated::edit',compact('advertiseaffiliated'));  
          return redirect()->route('advertiseaffiliated.edit',$slug);
        }else{
            Session::flash('error', trans('flash.error.record_not_available_now'));
            return redirect()->route('backend.dashboard');
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
    public function status(Request $request,$id)
    {
        $response = $this->AdvertiseAffiliatedRepo->changeStatus($request,$id);
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
    public function update(UpdateAdvertiseAffiliatedRequest $request, $slug)
    {
        $data =  $this->AdvertiseAffiliatedRepo->getRecord($slug);
        if($data){
            $response = $this->AdvertiseAffiliatedRepo->update($request,$slug);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('advertiseaffiliated.edit',$slug);
        }
        Session::flash('error', trans('flash.error.record_not_available_now'));
        return redirect()->route('backend.dashboard');
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * upload Category picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(AdvertiseAffiliatedMediaRequest $request) {
        try {
            $response = $this->AdvertiseAffiliatedRepo->saveProductPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
