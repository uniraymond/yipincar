<?php

use Illuminate\Database\Seeder;
use App\ArticleStatus;

class ArticleStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $article_status = new ArticleStatus();
        $article_status->name = 'draft';
        $article_status->title = '草稿';
        $article_status->comment = '草稿';
        $article_status->save();

        $article_status = new ArticleStatus();
        $article_status->name = 'review';
        $article_status->title = '初审';
        $article_status->comment = '初审';
        $article_status->save();

        $article_status = new ArticleStatus();
        $article_status->name = 'final_review';
        $article_status->title = '终审';
        $article_status->comment = '终审';
        $article_status->save();

        $article_status = new ArticleStatus();
        $article_status->name = 'publish';
        $article_status->title = '发布';
        $article_status->comment = '发布';
        $article_status->save();

//        $article_status = new ArticleStatus();
//        $article_status->name = 'reject';
//        $article_status->title = '驳回';
//        $article_status->comment = '驳回';
//        $article_status->save();
    }
}
