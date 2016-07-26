<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('articles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('content');
            $table->integer('category_id')->references('id')->on('categories');
            $table->integer('type_id')->references('id')->on('articletypes');
            $table->timestamp('created_at');
            $table->integer('created_by')->references('id')->on('users');
            $table->timestamp('updated_at');
            $table->integer('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}
