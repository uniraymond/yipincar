<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<= 10; $i++) {
            DB::table('articles')->insert([
                'title' => str_random(5),
                'description' => str_random(10),
                'content'=>'this is content',
                'category_id' => 1,
                'created_by' => 5,
                'type_id' => 1
            ]);

        }
    }
}
