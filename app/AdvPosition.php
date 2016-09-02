<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvPosition extends Model
{
  public function adv_setttings()
  {
    return $this->hasMany('App\AdvSettings', 'position_id');
  }
}
