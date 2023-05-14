<?php

namespace Modules\Permissions\Repositories;

use DB,Mail,Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Route;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;
use DataTables;


class PermissionsRepository implements PermissionsRepositoryInterface {

    public $Role;
    public $PermissionGroups;

    function __construct(Role $Role,PermissionGroups $PermissionGroups,Permission $Permission) {
        $this->Role = $Role;
        $this->PermissionGroups = $PermissionGroups;
        $this->Permission = $Permission;
    }

    public function getRecord($id)
    {
      return $this->Permission->find($id);
    } 

    public function getRecordBySlug($slug)
    {
      return $this->Permission->where('slug',$slug)->first();
    }

    public function getAll()
    {
      return $this->Permission->all();
    }

    public function getAllWithGroupByGroupName()
    {
      return $this->getAll()->groupBy('group_name');
    }

    public function getAllDatatable($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
        $lists = $this->Permission->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
                            ->get();       
        return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['permissions.destroy', [$list->slug]], 'edit'=>['permissions.edit', [$list->slug]]]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    return $edit;
                })         
                ->editColumn('description', function($list){
                    return substr(strip_tags($list->description), 0, $limit = 30) . $end = "...";
                })
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }         
    }

    public function getRoute()
    {
      $action = [];
       foreach (Route::getRoutes()->getRoutes() as $route)
       {
           $action[] = $route->getAction();           
       }
       return $action;
    }

    public function createPermissions()
    {
        $routes = $this->getRoute();
        foreach($routes as $ro)
        {
            if(isset($ro['as']))
            {
                //create permission groups auto
                $group = $this->PermissionGroups->createPermissionGroup($ro['as']);
                $array['name'] = $ro['as'];
                $array['group_name'] = $group;
                $array['display_name'] = $ro['as'];
                $array['description'] = $ro['as'];
                app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
                $leave = array('debugbar','debugbar.assets','debugbar.cache','ignition','passport.authorizations','passport.tokens','passport.token','passport.clients','passport.scopes'); 
                if (!in_array($group, $leave)) 
                  { 
                   Permission::permissionCreate($array);
                  } 
            }
        }
    }

    public function assignRolePermissions($request,$slug)
    {
        $role = $this->Role->where('slug',$slug)->first();

        if($role)
        {
            return $role->syncPermissions($request->get('permission_id'));
        }  
    }

    public function getPermissionGroups()
    {
       return $this->PermissionGroups->where('status',1)->pluck('name','name');
    }

    public function getPermissionRouteLists()
    {
       return $this->Permission->getPermissionRouteLists();
    }

    public function update($request,$id)
    {
        return $this->Permission->UpdatePermission($request,$id); 
    }

    public function destroy($id)
    {
      return $this->Permission->destroy($id);
    }
}
