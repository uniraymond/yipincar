<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProveToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('prove_type');
            $table->string('prove_number');
            $table->string('prove_resource');

            $table->string('auth_resource');
            $table->string('targetArea');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('prove_type');
            $table->dropColumn('prove_number');
            $table->dropColumn('prove_resource');
            $table->dropColumn('auth_resource');
            $table->dropColumn('targetArea');
        });
    }
}
