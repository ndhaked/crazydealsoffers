<?php

namespace Modules\Roles\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Roles\Http\Requests\CreateRoleRequest;
use Modules\Roles\Http\Requests\UpdateRoleRequest;
use Modules\Roles\Repositories\RolesRepositoryInterface as RolesRepo;

class RolesController extends Controller
{
    public function __construct(RolesRepo $RolesRepo)
    {
        $this->middleware(['ability','auth'])->except('getAjaxData');
        $this->RolesRepo = $RolesRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('roles::index');
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->RolesRepo->getAll($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('roles::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {
        $this->RolesRepo->store($request);
        return redirect()->route('roles.index')
                        ->with('success',trans('flash.success.roles_created_successfully'));  
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('roles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$slug)
    {
        $role =  $this->RolesRepo->edit($request,$slug);
        if($role){
          return view('roles::edit',compact('role'));  
        }
        Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
        return redirect()->route('roles.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateRoleRequest $request, $slug)
    {
        $role =  $this->RolesRepo->getRecordBySlug($slug);
        if($role){
            $this->RolesRepo->update($request,$role->id);
            return redirect()->route('roles.index');
        }
        Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
        return redirect()->route('roles.index');
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->RolesRepo->getRecord($id);
            if($data){
                $this->RolesRepo->destroy($id);
                Session::flash('success', trans('flash.success.role_deleted_successfully'));
                return redirect()->route('roles.index');
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('roles.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('roles.index');
        }
    }

}
