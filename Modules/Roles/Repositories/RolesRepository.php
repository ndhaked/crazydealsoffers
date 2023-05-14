<?php

namespace Modules\Roles\Repositories;

use Modules\EmailTemplates\Entities\EmailTemplate;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;
use Spatie\Permission\Models\Permission;


class RolesRepository implements RolesRepositoryInterface {

    public $Role;

    function __construct(Role $Role) {
        $this->Role = $Role;
    }

    public function getRecord($id)
    {
      return $this->Role->find($id);
    } 

    public function getRecordBySlug($slug)
    {
      return $this->Role->where('slug',$slug)->first();
    }

    public function getAll($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $lists = $this->Role->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->orderBy('id','desc')->get();  
            return DataTables::of($lists)
               ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['roles.destroy', [$list->slug]], 'edit'=>['roles.edit', [$list->slug]],'permission'=>['roles.premission.create', [$list->slug]]]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = '';
                    if(auth()->user()->hasAnyPermission('roles.destroy')){
                        $delete = keyExist($dispalyButton, 'delete');
                    }
                   
                    $permission = keyExist($dispalyButton, 'permission');
                    return $edit.$permission.$delete;
                })  
                ->editColumn('display_name', function($list){
                    return ucfirst($list->name);
                })
                ->editColumn('created_at', function($list){
                    return date_format($list->created_at,"m/d/Y");
                })
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('name','display_name','description');
            $role = $this->Role->create($filleable);
            $role->syncPermissions($request->input('permission'));
            Session::flash('success', trans('flash.success.email_template_created_successfully'));
        }catch (Exception $ex) {
            Session::flash('error', trans('flash.error.oops_something_went_wrong_creating_record'));
        }        
    }

    public function edit($request,$slug)
    {
        return $this->Role->findBySlug($slug);
    } 

    public function update($request,$id)
    {
     try {
        $filleable = $request->only('name','display_name','description');
        $role = $this->Role->find($id);
        $role->fill($filleable);
        $role->save();
            Session::flash('success', trans('flash.success.roles_updated_successfully'));
        }catch (Exception $ex) {
            Session::flash('error', trans('flash.error.oops_something_went_wrong_updating_record'));
        } 
    }

    public function destroy($id)
    {
      return $this->Role->destroy($id);
    }

}
