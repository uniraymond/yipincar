<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jisu_id');
            $table->integer('parentid');
            $table->string('name');
            $table->string('fullname')->nullable();
            $table->string('initial')->nullable();
            $table->string('logo')->nullable;
            $table->string('salestate')->nullable();
            $table->integer('depth')->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cars');
    }
}
