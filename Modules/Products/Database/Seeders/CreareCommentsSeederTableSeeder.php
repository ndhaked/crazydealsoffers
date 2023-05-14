<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class CreareCommentsSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $groupname = 'Product Comments';
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
           'commentnotifications.index',
           'commentnotifications.productComments',
           'commentnotifications.getUsersListForTag',
           'commentnotifications.addComments',
           'commentnotifications.addCommentReply',
           'commentnotifications.destroy',
        ];

        $display = [
           'view_comment_notifications',
           'view_product_comments',
           'get_user_lists_for_tags',
           'add_comment',
           'add_comment_reply',
           'delete_comment',
           'remove_product',
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
