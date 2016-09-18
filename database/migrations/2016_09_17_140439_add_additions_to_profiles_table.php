<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionsToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('media_type_id')->references('id')->on('object_types');
            $table->integer('prove_id')->references('id')->on('object_types');
            $table->integer('resource_id')->references('id')->on('recource');
            $table->integer('city_id')->references('id')->on('object_types');
            $table->string('email');
            $table->string('auth_resource_id')->reference('id')->on('recource');
            $table->string('ass_resource');
            $table->string('weixin_public_id');
            $table->string('media_name');
            $table->string('media_icon')->reference('id')->on('recource');
            $table->text('media_description');
            $table->tinyInteger('agree');
            $table->string('orgnise_icon')->reference('id')->on('recource');
            $table->string('contract_auth');
            $table->string('self_url');
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
            $table->dropColumn('media_type_id');
            $table->dropColumn('prove_id');
            $table->dropColumn('resource_id');
            $table->dropColumn('city_id');
            $table->dropColumn('email');
            $table->dropColumn('auth_resource_id');
            $table->dropColumn('ass_recource');
            $table->dropColumn('weixin_public_id');
            $table->dropColumn('media_name');
            $table->dropColumn('media_icon');
            $table->dropColumn('media_description');
            $table->dropColumn('agree');
            $table->dropColumn('orgnise_icon');
            $table->dropColumn('contract_auth');
            $table->dropColumn('self_url');
        });
    }
}
