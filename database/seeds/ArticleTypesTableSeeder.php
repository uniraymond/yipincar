<?php

use Illuminate\Database\Seeder;
use App\ArticleTypes;

class ArticleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articleType = new ArticleTypes();
        $articleType->name = '推广';
        $articleType->description = '推广';
        $articleType->save();

        $articleType = new ArticleTypes();
        $articleType->name = '文章';
        $articleType->description = '文章';
        $articleType->save();
    }
}
