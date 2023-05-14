<?php

namespace Modules\EmailTemplates\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class EmailTemplatesPermissionSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();
        $groupname = 'Email Templates';
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
           'email-templates.index',
           'email-templates.create',
           'email-templates.edit',
           'email-templates.destroy'
        ];

        $display = [
           'view_emailtemplates',
           'add_emailtemplates',
           'edit_emailtemplates',
           'remove_emailtemplates'
        ];

        foreach ($permissions as $k => $permission) {
                Permission::permissionCreate([
                    'guard_name'=>'admin',
                    'name' => $permission,
                    'group_name' => $groupname,
                    'display_name' => $display[$k]
                ]);
        }
    }
}
