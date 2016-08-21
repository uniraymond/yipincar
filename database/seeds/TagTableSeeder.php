<?php

use Illuminate\Database\Seeder;
use App\Tags;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag = new Tags();
        $tag->name = '广告';
        $tag->description = '广告';
        $tag->save();

        $tag = new Tags();
        $tag->name = '汽车';
        $tag->description = '汽车';
        $tag->save();

        $tag = new Tags();
        $tag->name = '公信力';
        $tag->description = '公信力';
        $tag->save();
    }
}
