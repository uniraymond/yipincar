<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'super_admin',
            'description' => 'Supper Admin'
        ]);

        DB::table('roles')->insert([
            'name' => 'admin',
            'description' => 'Admin'
        ]);

        DB::table('roles')->insert([
            'name' => 'editor',
            'description' => 'Editor'
        ]);

        DB::table('roles')->insert([
            'name' => 'user',
            'description' => 'User'
        ]);
    }
}
