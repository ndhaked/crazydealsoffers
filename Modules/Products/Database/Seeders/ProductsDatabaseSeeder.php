<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Spatie\PermissionGroups;
use App\Models\Spatie\Permission;

class ProductsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $groupname = 'Products';
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
           'product.index',
           'product.create',
           'product.store',
           'product.edit',
           'product.show',
           'product.update',
           'product.destroy',
           'product.uploadcsv',
           'product.importcsv',
           'product.deal_of_the_day',
           'product.uploadMedia',
           'product.samplecsv',
           'product.status',
        ];

        $display = [
           'view_product',
           'add_product',
           'save_product',
           'edit_product',
           'details_product',
           'update_product',
           'remove_product',
           'get_import_product_form',
           'emport_csv_product',
           'make_deal_of_the_day_product',
           'upload_product_media',
           'download_sample_csv',
           'active_deactive_product',
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
