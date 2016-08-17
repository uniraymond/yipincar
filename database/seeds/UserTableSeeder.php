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
        $role_editor = Role::where('name', 'editor')->first();
        $role_user = Role::where('name', 'user')->first();
        $role_visitor = Role::where('name', 'visitor')->first();

        $user = new User();
        $user->name = 'raymond';
        $user->email = 'raymond@localhost';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach($role_super_admin);

        $user = new User();
        $user->name = 'selynne';
        $user->email = 'selynne@localhost';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'simon';
        $user->email = 'simon@localhost';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach($role_editor);

        $user = new User();
        $user->name = 'mike';
        $user->email = 'mike@localhost';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach($role_user);

        $user = new User();
        $user->name = 'david';
        $user->email = 'david@localhost';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach($role_visitor);
    }
}
