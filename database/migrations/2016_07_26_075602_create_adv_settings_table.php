<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adv_settings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('displaytime');
            $table->integer('displaynumber');
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
        Schema::drop('adv_settings');
    }
}
