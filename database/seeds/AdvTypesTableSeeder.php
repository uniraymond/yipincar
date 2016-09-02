<?php

use Illuminate\Database\Seeder;
use App\AdvType;

class AdvTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $advType = new AdvType();
        $advType->name = '图片';
        $advType->title = 'image';
        $advType->description = '图片广告';
        $advType->save();

        $advType = new AdvType();
        $advType->name = '视频';
        $advType->title = 'vedio';
        $advType->description = '视频广告';
        $advType->save();
    }
}
