<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvSettingIdToArticleStatusChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_status_checks', function (Blueprint $table) {
            $table->integer('adv_setting_id')->references('id')->on('adv_settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_status_checks', function (Blueprint $table) {
            $table->dropColumn('adv_setting_id');
        });
    }
}
