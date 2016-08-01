<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Article extends Model
{
  public static function getAllArticles()
  {
    $list = DB::table('articles')
                ->join('categories', 'articles.category_id', '=', 'categories.id')
                ->join('article_types', 'articles.type_id', '=', 'article_types.id')
                ->leftJoin('article_resource', 'articles.id', '=', 'article_resource.artcle_id')
                ->leftJoin('resources', 'article_resource.resource_id', '=', 'resources.id')
                ->select('articles.*',
                    'categories.name as categoryName',
                    'article_types.name as articletypeName',
                    'resources.name as resourceName',
                    'resources.link as resourceLink'
                     )
                ->get();

    return $list;
  }

  public static function getArticle($id)
  {
    $article = DB::table('articles')
        ->join('categories', 'articles.category_id', '=', 'categories.id')
        ->join('article_types', 'articles.type_id', '=', 'article_types.id')
        ->select('articles.*', 'categories.name as categoryName', 'article_types.name as articletypeName')
        ->where('articles.id', '=', $id)
        ->get();

    return $article;
  }
}
