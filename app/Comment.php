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

  public function user_created_by()
  {
    return $this->belongsTo('App\User', 'created_by');
  }

  public function user_updated_by()
  {
    return $this->belongsTo('App\User', 'updated_by');
  }
}
