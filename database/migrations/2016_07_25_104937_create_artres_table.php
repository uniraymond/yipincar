<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artres', function (Blueprint $table) {
           $table->increments('id');
            $table->integer('artcle_id')->references('id')->on('articles');
            $table->integer('resource_id')->references('id')->on('resources');
            $table->integer('displayorder');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('artres');
    }
}
