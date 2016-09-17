<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAdvSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adv_settings', function (Blueprint $table) {
            $table->string('status')->references('id')->on('article_statuses');
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
            $table->dropColumn('status');
        });
    }
}
