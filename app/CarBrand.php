<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    protected $fillable = ['name', 'initial', 'parentid', 'logo', 'depth', 'jisu_id'];
}
