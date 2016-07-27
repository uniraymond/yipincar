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
                ->join('articletypes', 'articles.type_id', '=', 'articletypes.id')
                ->join('artres', 'articles.id', '=', 'artres.artcle_id')
                ->join('resources', 'artres.resource_id', '=', 'resources.id')
                ->select('articles.*',
                    'categories.name as categoryName',
                    'articletypes.name as articletypeName',
                    'resources.name as resourceName',
                    'resources.link as resourceLink')
                ->get();

    return $list;
  }

  public static function getArticle($id)
  {
    $article = DB::table('articles')
        ->join('categories', 'articles.category_id', '=', 'categories.id')
        ->join('articletypes', 'articles.type_id', '=', 'articletypes.id')
        ->select('articles.*', 'categories.name as categoryName', 'articletypes.name as articletypeName')
        ->where('articles.id', '=', $id)
        ->get();

    return $article;
  }
}
