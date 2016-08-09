<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  public function Article()
  {
    return $this->belongsTo('App\Article', 'article_id');
  }

  public function Zan()
  {
    return $this->hasMany('App\Zan');
  }
}
