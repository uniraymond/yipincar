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
            $table->string('name');
            $table->string('title');
            $table->text('description');
            $table->integer('resource_id')->references('id')->on('resources')->onDelete('cascade')->nullable();
            $table->dateTime('published_at');
            $table->dateTime('finished_at');
            $table->tinyInteger('order');
            $table->tinyInteger('top');
            $table->integer('position_id')->references('id')->on('adv_positions')->onDelete('cascade');
            $table->integer('type_id')->references('id')->on('adv_types')->onDelete('cascade');
            $table->integer('displaytime');
            $table->string('links');
            $table->integer('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->tinyInteger('status')->references('id')->on('article_statuses')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at');
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
