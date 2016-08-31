<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTypes extends Model
{
  public function articles()
  {
    return $this->hasMany('App\Article', 'type_id');
  }
}
