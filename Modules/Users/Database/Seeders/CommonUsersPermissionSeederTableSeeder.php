<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class CommonUsersPermissionSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Users';
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
           'users.status',
           'users.uploadProfile',
           'users.storeChangeUserPassword',
        ];

        $display = [
           'activate_deactivate_user',
           'change_profile_picture',
           'change_password',
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
