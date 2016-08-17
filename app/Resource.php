<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //
  public static function storeDB($request)
  {
    
  }

  public function articles()
  {
    return $this->belongsToMany('App\Article', 'article_resources', 'resource_id', 'article_id');
  }

}
