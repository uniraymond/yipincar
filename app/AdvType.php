<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvType extends Model
{
  public function adv_settings()
  {
    return $this->hasMany('adv_settings', 'type_id');
  }
}
