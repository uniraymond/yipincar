<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdToAdvSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adv_settings', function (Blueprint $table) {
            $table->integer('template_id')->references('id')->on('adv_tempates')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adv_settings', function (Blueprint $table) {
            $table->drop('template_id');
        });
    }
}
