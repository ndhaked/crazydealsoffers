<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class AdvertisementsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupname = 'Advertisements';
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
           'advertisement.index',
           'advertisement.create',
           'advertisement.store',
           'advertisement.edit',
           'advertisement.update',
           'advertisement.destroy',
           'advertisement.status',
           'advertisement.uploadMedia',
           'advertisement.ajaxdata',
        ];

        $display = [
           'view_advertisement',
           'add_advertisement',
           'save_advertisement',
           'edit_advertisement',
           'update_advertisement',
           'remove_advertisement',
           'remove_active_deactive_status',
           'remove_upload_media',
           'remove_advertisement_ajax_data',
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
