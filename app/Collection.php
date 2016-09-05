<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    public static function getCollection($id)
    {
        $collection = DB::table('collections')
            ->join('users', 'users.id', '=', 'collections.user_id')
            ->join('articles', 'articles.id', '=', 'collections.article_id')
            ->select('articles.*')
            ->where('collections.id', '=', $id)
            ->get();

        return $collection;
    }
}
