<?php

namespace Modules\Categories\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
       $groupname = 'Categories';
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
           'categories.index',
           'categories.create',
           'categories.edit',
           'categories.destroy',
        ];

        $display = [
           'view_categories',
           'add_categories',
           'edit_categories',
           'remove_categories',
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
