<?php

use Illuminate\Database\Seeder;

class ArticleTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<= 11; $i++) {
            DB::table('article_tags')->insert([
                'article_id' => $i,
                'tag_id' => 1
            ]);

        }
    }
}
