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

  public function resource_types() {
    return $this->belongsTo('App\ResourceTypes', 'type_id');
  }

  public function article_resources()
  {
    return $this->hasMany('App\ArticleResources');
  }
}
