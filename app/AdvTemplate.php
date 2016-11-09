<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvTemplate extends Model
{
    public function adv_setttings()
    {
        return $this->hasMany('App\AdvSettings', 'template_id');
    }
}
