<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Resource as Resource;

class AdvSetting extends Model
{
    public static function getAdvImages() {
      $list = DB::table('resources')
          ->where('type_id', '=', 1)
          ->get();

      return $list;
    }

    public static function getAdvImage($id) {
      return Resource::findOrFail($id);
    }
  
    public static function updateImage($request) {
      $res = Resource::find($request['id']);
      $res->description = $request['description'];
      $res->save();

      return self::getAdvImage($request['id']);
    }

    public static function updateAllImages($request, $user_id) {
      $images = self::getAdvImages();

      foreach ($images as $image) {
        $imageId = $image->id;
        $res = Resource::find($imageId);
        if ($request['delete_'.$imageId]) {
          $res->delete();
        } else {
          $res->published = $request['published_'.$imageId] ? 1 : 0;
          $res->order = $request['order_'.$imageId];
          $res->updated_by = $user_id;
          $res->save();
        }
      }

      return;
    }
}
