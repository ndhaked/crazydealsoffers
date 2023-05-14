<?php

namespace Modules\Permissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Permissions\Http\Requests\UpdatePermissionRequest;
use Modules\Permissions\Repositories\PermissionsRepositoryInterface as PermissionsRepo;
use Modules\Roles\Repositories\RolesRepositoryInterface as RolesRepo;

class PermissionsController extends Controller
{

     public function __construct(PermissionsRepo $PermissionsRepo,RolesRepo $RolesRepo)
    {
        $this->middleware(['ability','auth'])->except('getAjaxData');
        $this->RolesRepo = $RolesRepo;
        $this->PermissionsRepo = $PermissionsRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('permissions::index');
    }


    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->PermissionsRepo->getAllDatatable($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     * This function is removed by @Developer Nirbhay
     */
    public function create()
    {
        $this->PermissionsRepo->createPermissions();
        session()->flash('success', trans('flash.success.permission_has_been_successfully_reloaded'));
        return redirect()->route('permissions.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $permission =  $this->PermissionsRepo->getRecordBySlug($slug);
        if($permission){
          $permissionGroups = $this->PermissionsRepo->getPermissionGroups();
          $routes = $this->PermissionsRepo->getPermissionRouteLists();
          return view('permissions::edit',compact('permission','permissionGroups','routes'));  
        }
        session()->flash('error', trans('flash.error.oops_reocrd_not_available'));
        return redirect()->route('permissions.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdatePermissionRequest $request, $slug)
    {
        $permission =  $this->PermissionsRepo->getRecordBySlug($slug);
        if($permission){
            $this->PermissionsRepo->update($request,$permission->id);
            $request->session()->flash('success', trans('flash.success.permission_has_been_successfully_updated'));
            return redirect()->route('permissions.index');             
        }
        session()->flash('error', trans('flash.error.oops_reocrd_not_available'));
        return redirect()->route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($slug)
    {
        try{
            $data =  $this->PermissionsRepo->getRecordBySlug($slug);
            if($data){
                $this->PermissionsRepo->destroy($data->id);
                Session::flash('success', trans('flash.success.permission_deleted_successfully'));
                return redirect()->route('permissions.index');
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('permissions.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('permissions.index');
        }
    }

    public function getPermission($slug)
    {
        $groupByPermission =  $this->PermissionsRepo->getAllWithGroupByGroupName();
        try {            
            $role = $this->RolesRepo->getRecordBySlug($slug);
            return view('permissions::role_permissions', compact('role', 'groupByPermission'));
        } catch (\Exception $ex) {
            session()->flash('warning', trans('flash.error.oops_something_went_wrong_invalid_access'));
            return redirect()->route('roles.index');
        }     
    }
    
    public function postPermission(Request $request,$slug)
    {
        try {
           return $this->PermissionsRepo->assignRolePermissions($request,$slug);
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
        }       
    }
}
