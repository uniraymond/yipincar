<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->date('dob');
            $table->string('address');
            $table->string('aboutself');
            $table->text('comment');
            $table->enum('gender', array('male', 'female'));
            $table->string('phone');
            $table->string('cellphone');
            $table->string('weibo_id');
            $table->string('weibo_name');
            $table->string('weixin_id');
            $table->string('weixin_name');
            $table->string('qq_id');
            $table->string('qq_name');
            $table->string('icon_uri');

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
            
            $table->integer('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles');
    }
}
