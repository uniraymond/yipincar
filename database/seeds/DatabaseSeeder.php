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

        $this->call(RolesTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ArticleTagsTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ArticleStatusTableSeeder::class);
        $this->call(ArticleTypesTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(AdvPositionsTableSeeder::class);
        $this->call(AdvTypesTableSeeder::class);
        $this->call(UserStatusTableSeeder::class);

        Model::reguard();
    }
}
