<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        	'name' => 'Hardik Savani', 
        	'email' => 'lara8@yopmail.com',
        	'password' => bcrypt('12345678')
        ]);

        $role = Role::create(['name' => 'admin','guard_name' => 'admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
