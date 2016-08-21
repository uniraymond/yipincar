<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYipinlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yipinlogs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('action');
            $table->text('origin');
            $table->text('target');
            $table->text('comment');
            $table->integer('action_id');
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
        Schema::drop('yipinlogs');
    }
}
