<?php

namespace Modules\Configuration\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Configuration\Http\Requests\CreateConfigurationRequest;
use Modules\Configuration\Http\Requests\UpdateConfigurationRequest;
use Modules\Configuration\Repositories\ConfigurationRepositoryInterface as ConfigurationRepo;

class ConfigurationController extends Controller
{
    
    public function __construct(ConfigurationRepo $ConfigurationRepo)
    {
        $this->middleware(['ability','auth']);
        $this->ConfigurationRepo = $ConfigurationRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    { 
        if(request()->ajax()) 
        {
            return $this->ConfigurationRepo->getAjaxData($request);
        }
        return view('configuration::index')->withModel('configuration');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('configuration::create')->withModel('configuration');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateConfigurationRequest $request)
    {
        $response = $this->ConfigurationRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('configuration.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$slug)
    {
        $data =  $this->ConfigurationRepo->getRecordBySlug($slug);
        if($data){
          return view('configuration::edit',compact('data'))->withModel('configuration');  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('configuration.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateConfigurationRequest $request, $id)
    {
        $data =  $this->ConfigurationRepo->getRecord($id);
        if($data){
            $response = $this->ConfigurationRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('configuration.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('configuration.index');
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->ConfigurationRepo->getRecord($id);
            if($data){
                $this->ConfigurationRepo->destroy($id);
                Session::flash('success', trans('flash.success.static_page_deleted_successfully'));
                return redirect()->route('configuration.index');
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('configuration.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('configuration.index');
        }
    }
}
