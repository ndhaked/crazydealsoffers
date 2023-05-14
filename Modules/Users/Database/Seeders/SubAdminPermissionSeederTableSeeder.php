<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class SubAdminPermissionSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Sub Admin';
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
           'subadmin.index',
           'subadmin.create',
           'subadmin.edit',
           'subadmin.destroy',
           'subadmin.show',
        ];

        $display = [
           'view_subadmin',
           'add_subadmin',
           'edit_subadmin',
           'remove_subadmin',
           'details_subadmin',
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
