<?php

namespace Modules\Notifications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class NotificationsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Notifications';
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
           'notifications.index',
           'notifications.create',
           'notifications.store',
           'notifications.edit',
           'notifications.update',
           'notifications.destroy',
        ];

        $display = [
           'view_notifications',
           'add_notifications',
           'save_notifications',
           'edit_notifications',
           'update_notifications',
           'remove_notifications',
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
