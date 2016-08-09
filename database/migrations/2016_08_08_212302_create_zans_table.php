<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->references('id')->on('zans')->onDelete('cascade');
            $table->integer('comment_id')->references('id')->on('zans')->onDelete('cascade');
            $table->integer('token');
            $table->integer('comfirmed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zans');
    }
}
