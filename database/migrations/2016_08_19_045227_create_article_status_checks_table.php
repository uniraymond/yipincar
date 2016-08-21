<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleStatusChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_status_checks', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->integer('article_status_id')->references('id')->on('article_status')->onDelete('cascade');
            $table->text('comment');
            $table->boolean('checked');
            $table->timestamps();
            $table->integer('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_status_checks');
    }
}
