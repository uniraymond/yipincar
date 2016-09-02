<?php

use Illuminate\Database\Seeder;
use App\AdvPosition;

class AdvPositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $advPosition = new AdvPosition();
        $advPosition->name = '开屏';
        $advPosition->title = 'open';
        $advPosition->description = '打开app';
        $advPosition->save();

        $advPosition = new AdvPosition();
        $advPosition->name = '轮播';
        $advPosition->title = 'turning';
        $advPosition->description = '轮播的广告';
        $advPosition->save();

        $advPosition = new AdvPosition();
        $advPosition->name = '文章';
        $advPosition->title = 'article';
        $advPosition->description = '文章里的广告';
        $advPosition->save();
    }
}
