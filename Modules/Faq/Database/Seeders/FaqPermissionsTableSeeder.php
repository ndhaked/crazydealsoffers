<?php

namespace Modules\Faq\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class FaqPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'FAQ';
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
           'faq.index',
           'faq.create',
           'faq.edit',
           'faq.destroy',
        ];

        $display = [
           'view_faq',
           'add_faq',
           'edit_faq',
           'remove_faq',
           'activate_deactivate_faq',
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
