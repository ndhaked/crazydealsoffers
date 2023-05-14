<?php

namespace Modules\Permissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class PermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Permissions';
        if($groupname){
            $group = PermissionGroups::where('name',$groupname)->count();
            if($group == 0)
            {
                PermissionGroups::create([
                    'name' => ucwords($groupname)
                ]); 
            }
        }
        $permissions = [
           'permissions.index',
           'permissions.edit',
           'roles.premission.create',
           'roles.premission.store',
        ];

        $display = [
           'view_permissions',
           'edit_permissions',
           'view_role_permissions',
           'update_role_permissions',
        ];

        foreach ($permissions as $k => $permission) {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            Permission::permissionCreate([
                'guard_name'=>'admin',
                'name' => $permission,
                'group_name' => $groupname,
                'display_name' => $display[$k]
            ]);
        }
    }
}
