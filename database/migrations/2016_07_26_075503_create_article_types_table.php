<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_types', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description');
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
        Schema::drop('article_types');
    }
}
