<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleStatusCheck extends Model
{
  public function articles()
  {
    return $this->belongsTo('App\Article', 'article_id');
  }
  public function article_status()
  {
    return $this->belongsTo('App\ArticleStatus', 'article_status_id');
  }
}
