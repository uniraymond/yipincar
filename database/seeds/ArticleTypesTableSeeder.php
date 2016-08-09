<?php

use Illuminate\Database\Seeder;

class ArticleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<= 11; $i++) {
            DB::table('article_types')->insert([
                'article_id' => $i,
                'type_id' => 1
            ]);

        }
    }
}
