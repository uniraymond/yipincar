<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentificationToAdvSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adv_settings', function (Blueprint $table) {
            $table->integer('identification')->references('id')->on('adv_idents')->onDelete('cascade')->default(1);;
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
            //
        });
    }
}
