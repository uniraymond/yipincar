<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zan extends Model
{
  public function Comment()
  {
    return $this->belongsTo('App\Comment', 'comment_id');
  }

  public function Article()
  {
    return $this->belongsTo('App\Article', 'article_id');
  }
}
