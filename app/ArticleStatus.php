<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model
{
  public function articles()
  {
    return $this->belongsToMany('App\Article', 'articles', 'article_status_id', 'article_id');
  }

  public function article()
  {
    return $this->hasMany('App\Article');
  }
}
