<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_types', function (Blueprint $table) {
            $table->integer('media_type_id')->references('id')->on('object_type');
            $table->integer('prove_id')->references('id')->on('object_type');
            $table->integer('recource_id')->references('id')->on('recource');
            $table->integer('city_id')->references('id')->on('object_type');
            $table->string('email');
            $table->string('mobile');
            $table->string('phone');
            $table->string('auth_rource_id')->reference('id')->on('recource');
            $table->string('ass_recouse');
            $table->string('weixin_public_id');
            $table->string('media_name');
            $table->string('media_icon')->reference('id')->on('recource');
            $table->text('media_description');
            $table->tinyInteger('agree');
            $table->string('orgnise_icon')->reference('id')->on('recource');
            $table->string('contract_auth');
            $table->string('self_url');
            $table->string('ass_resource');
            $table->string('self_media_name');
            $table->string('self_icon')->reference('id')->on('recource');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('resource_types');
    }
}
