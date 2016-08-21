<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB as DB;

class Yipinlog extends Model
{
  public static function createlog($array)
  {
    $name     = $array['name'];
    $action   = $array['action'];
    $origin   = $array['origin'];
    $target   = $array['target'];
    $comment  = $array['comment'];
    $created_by = $array['created_by'];

    DB::table('yipinlogs')->insert(
      [
          'name'        => $name,
          'action'      => $action,
          'origin'      => $origin,
          'target'      => $target,
          'comment'     => $comment,
          'created_by'  => $created_by
      ]
    );
  }
}
