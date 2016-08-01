<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model as Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(UserTableSeeder::class);
         $this->call(ArticlesTableSeeder::class);
         $this->call(RolesTableSeeder::class);

        Model::reguard();
    }
}
