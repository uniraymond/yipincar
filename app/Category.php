<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  public function categories()
  {
    return $this->hasMany('App\Category');
   }

  public function articles()
  {
    return $this->hasMany('App\Article');
  }
}
