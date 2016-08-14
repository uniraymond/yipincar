<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name = 'super_admin';
        $role_user->description = 'Super Admin';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'admin';
        $role_user->description = 'Admin';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'editor';
        $role_user->description = 'Editor';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'user';
        $role_user->description = 'User';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'visitor';
        $role_user->description = 'Visitor';
        $role_user->save();
    }
}
