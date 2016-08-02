<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AdvSetting extends Model
{
    public static function getAdvImages() {
      $list = DB::table('resources')
          ->where('type_id', '=', 1)
          ->get();

      return $list;
    }
}
