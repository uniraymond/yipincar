<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['name', 'fullname', 'initial', 'parentid', 'logo', 'depth', 'jisu_id', 'salestate'];
}
