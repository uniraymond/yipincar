<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  public function article()
  {
    return $this->belongsTo('App\Article', 'article_id');
  }

  public function zan()
  {
    return $this->hasMany('App\Zan');
  }
}
