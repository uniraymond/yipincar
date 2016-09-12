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
        $role_user->name = 'chef_editor';
        $role_user->description = '总编';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'main_editor';
        $role_user->description = '主编';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'editor';
        $role_user->description = '内容编辑';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'auth_editor';
        $role_user->description = '入驻编辑';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'adv_editor';
        $role_user->description = '广告编辑';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'technican';
        $role_user->description = '技术支持';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'editor';
        $role_user->description = '一品编辑';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'user';
        $role_user->description = '注册用户';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'visitor';
        $role_user->description = '非注册用户';
        $role_user->save();
    }
}
