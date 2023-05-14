<?php

namespace Modules\Contactus\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Contactus\Http\Requests\CreateContactusRequest;
use Modules\Contactus\Repositories\ContactusRepositoryInterface as ContactusRepo;

class ContactusController extends Controller
{
    public function __construct(ContactusRepo $ContactusRepo)
    {
        $this->middleware(['ability','auth'],['except' => ['store','create']]);
        $this->ContactusRepo = $ContactusRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    { 
        if(request()->ajax()) 
        {
            return $this->ContactusRepo->getAjaxData($request);
        }
        return view('contactus::index')->withModel('contactus');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->ContactusRepo->getRecordBySlug($slug);
            if($data){
                $this->ContactusRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.record_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('contactus.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('contactus.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('contactus.index');
        }
    }

    /*
     * Frontend Guest Routes
     *
    **/
    public function create()
    {
        $pageInfo = \Cache::remember('pageInfocontactus', 60, function () {
            return $this->ContactusRepo->getStaticPageBySlug('contactus');
        });
        return view('contactus::contactus',compact('pageInfo'))->withModel('contactus');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateContactusRequest $request)
    {
        $response = $this->ContactusRepo->store($request);
        return response()->json($response);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->back();
    }
}
