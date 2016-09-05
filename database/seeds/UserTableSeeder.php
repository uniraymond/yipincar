<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_super_admin = Role::where('name', 'super_admin')->first();
        $role_admin = Role::where('name', 'admin')->first();
        $role_chef_editor = Role::where('name', 'chef_editor')->first();
        $role_main_editor = Role::where('name', 'main_editor')->first();
        $role_editor = Role::where('name', 'editor')->first();
        $role_auth_editor = Role::where('name', 'auth_editor')->first();
        $role_advertisment_editor = Role::where('name', 'advertisment_editor')->first();
        $role_technican = Role::where('name', 'technican')->first();
        $role_user = Role::where('name', 'user')->first();
        $role_visitor = Role::where('name', 'visitor')->first();

        $user = new User();
        $user->name = 'raymond';
        $user->email = 'raymond@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_super_admin);
        $user->roles()->attach($role_admin);
        $user->roles()->attach($role_chef_editor);
        $user->roles()->attach($role_main_editor);
        $user->roles()->attach($role_editor);

        $user = new User();
        $user->name = 'yuan';
        $user->email = 'yuan@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_super_admin);
        $user->roles()->attach($role_chef_editor);
        $user->roles()->attach($role_main_editor);
        $user->roles()->attach($role_auth_editor);

        $user = new User();
        $user->name = 'superadmin';
        $user->email = 'superadmin@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_super_admin);

        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'chefeditor';
        $user->email = 'sichefeditormon@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_chef_editor);

        $user = new User();
        $user->name = 'maineditor';
        $user->email = 'maineditor@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_main_editor);

        $user = new User();
        $user->name = 'editor';
        $user->email = 'editor@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_editor);

        $user = new User();
        $user->name = 'autheditor';
        $user->email = 'autheditor@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_auth_editor);

        $user = new User();
        $user->name = 'adveditor';
        $user->email = 'adveditor@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_advertisment_editor);

        $user = new User();
        $user->name = 'technican';
        $user->email = 'technican@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_technican);

        $user = new User();
        $user->name = 'user';
        $user->email = 'user@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_user);

        $user = new User();
        $user->name = 'visitor';
        $user->email = 'visitor@localhost';
        $user->password = bcrypt('password');
        $user->status_id = 3;
        $user->pre_status_id = 3;
        $user->save();
        $user->roles()->attach($role_visitor);
    }
}
