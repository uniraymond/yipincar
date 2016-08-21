<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = '资讯';
        $category->description = '资讯(一级)';
        $category->category_id = 0;
        $category->save();

        $category = new Category();
        $category->name = '公信力';
        $category->description = '公信力(一级)';
        $category->category_id = 0;
        $category->save();

        $category = new Category();
        $category->name = '动态';
        $category->description = '动态';
        $category->category_id = 1;
        $category->save();

        $category = new Category();
        $category->name = '新闻';
        $category->description = '新闻';
        $category->category_id = 1;
        $category->save();

        $category = new Category();
        $category->name = '一品汇';
        $category->description = '一品汇';
        $category->category_id = 1;
        $category->save();

        $category = new Category();
        $category->name = '用车';
        $category->description = '用车';
        $category->category_id = 1;
        $category->save();

        $category = new Category();
        $category->name = '测评';
        $category->description = '测评';
        $category->category_id = 1;
        $category->save();

        $category = new Category();
        $category->name = '订阅';
        $category->description = '订阅';
        $category->category_id = 1;
        $category->save();

        $category = new Category();
        $category->name = '榜单';
        $category->description = '榜单';
        $category->category_id = 2;
        $category->save();

        $category = new Category();
        $category->name = '智库';
        $category->description = '智库';
        $category->category_id = 2;
        $category->save();

        $category = new Category();
        $category->name = '产品';
        $category->description = '产品';
        $category->category_id = 10;
        $category->save();

        $category = new Category();
        $category->name = '市场';
        $category->description = '市场';
        $category->category_id = 10;
        $category->save();

        $category = new Category();
        $category->name = '行业';
        $category->description = '行业';
        $category->category_id = 10;
        $category->save();
    }
}
