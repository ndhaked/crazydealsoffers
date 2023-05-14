<?php

namespace Modules\Blogs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class BlogsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Blogs';
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
           'blog.index',
           'blog.create',
           'blog.store',
           'blog.edit',
           'blog.update',
           'blog.destroy',
           'blog.status',
           'blog.uploadMedia',
           'blog.ajaxdata',
        ];

        $display = [
           'view_blogs',
           'add_blogs',
           'save_blogs',
           'edit_blogs',
           'update_blogs',
           'remove_blogs',
           'remove_active_deactive_status',
           'remove_upload_media',
           'remove_blogs_ajax_data',
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
