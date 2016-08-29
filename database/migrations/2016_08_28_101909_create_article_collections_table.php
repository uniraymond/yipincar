<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->integer('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_collections');
    }
}
