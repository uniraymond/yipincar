<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTags extends Model
{
  public function articles()
  {
    return $this->belongsTo('App\Article');
  }

  public function tags()
  {
    return $this->belongsTo('App\Tags');
  }
}
