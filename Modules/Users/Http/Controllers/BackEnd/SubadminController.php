<?php

namespace Modules\Users\Http\Controllers\BackEnd;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\CreateSubadminRequest;
use Modules\Users\Http\Requests\UpdateSubadminRequest;
use Modules\Users\Repositories\UsersRepositoryInterface as UsersRepo;
use Modules\Users\Repositories\SubadminRepositoryInterface as SubadminRepo;

class SubadminController extends Controller
{
    protected $role = 'subadmin';
    public function __construct(UsersRepo $UsersRepo,SubadminRepo $SubadminRepo)
    {
        $this->middleware(['ability','auth']);
        $this->UsersRepo = $UsersRepo;
        $this->SubadminRepo = $SubadminRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        $users = $this->UsersRepo->getAll($request,$this->role);
        return view('users::admin.subadmin.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('users::admin.subadmin.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateSubadminRequest $request)
    {
        $response = $this->SubadminRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('subadmin.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug)
    { 
        $user =  $this->SubadminRepo->getRecordBySlugForAdminAndSubadmin($slug);
        if($user){
          return view('users::admin.subadmin.show',compact('user'));  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $user =  $this->SubadminRepo->getRecordBySlugForAdminAndSubadmin($slug);
        if($user){
          return view('users::admin.subadmin.edit',compact('user'));  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('subadmin.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateSubadminRequest $request, $id)
    {
        $data =  $this->SubadminRepo->getRecord($id);
        if($data){
            $response = $this->SubadminRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            if(auth()->user()->hasRole('admin') && auth()->user()->id == $data->id){
                Session::flash($response['type'], 'Profile updated successfully'); 
                return redirect()->route('subadmin.show',auth()->user()->slug);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('subadmin.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('subadmin.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->SubadminRepo->getRecord($id);
            if($data){
                $this->SubadminRepo->destroy($id);
                Session::flash('success', trans('flash.success.subadmin_deleted_successfully'));
                return redirect()->route('subadmin.index');
            }
            Session::flash('error', trans('flash.error.reocrd_not_available_now'));
            return redirect()->route('subadmin.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('subadmin.index');
        }
    }
}
