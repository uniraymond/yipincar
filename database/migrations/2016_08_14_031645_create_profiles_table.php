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
            $table->string('fname');
            $table->string('lname');
            $table->date('dob');
            $table->string('address');
            $table->enum('gender', array('male', 'female'));
            $table->string('phone');
            $table->string('cellphone');
            $table->string('weibo_name');
            $table->string('weixin_name');
            $table->string('qq_name');
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
