<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatacollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datacollection', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pathogen_name');
            $table->string('food_name');
            $table->integer('temp');
            $table->integer('time');
            $table->integer('cells');
            $table->integer('infectious_dosage');
            $table->integer('doubling_time');
            $table->double('growth_rate');
            $table->string('user_email');
            $table->string('person_type');
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
        Schema::dropIfExists('datacollection');
    }
}
