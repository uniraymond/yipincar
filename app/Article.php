<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Article extends Model
{
  public static function getAllArticles()
  {
    $list = DB::table('articles')
            ->paginate(5);

    return $list;
  }

  public function categories()
  {
    return $this->belongsTo('App\Category', 'category_id');
  }

  public function tags()
  {
    return $this->belongsToMany('App\Tags', 'article_tags', 'article_id', 'tag_id');
  }

  public function article_types() {
    return $this->belongsTo('App\ArticleTypes', 'type_id');
  }

  public function article_tags() {
    return $this->hasMany('App\ArticleTags');
  }

  public function comments() {
    return $this->hasMany('App\Comment');
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
