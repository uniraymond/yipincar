<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_resource', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artcle_id')->references('id')->on('articles')->onDelete('cascade');
            $table->integer('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->integer('displayorder');
            $table->timestamps();
            $table->integer('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_resource');
    }
}
