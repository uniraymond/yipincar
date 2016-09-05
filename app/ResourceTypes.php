<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceTypes extends Model
{
  public function resources()
  {
    return $this->hasMany('App\Resource', 'type_id');
  }
}
