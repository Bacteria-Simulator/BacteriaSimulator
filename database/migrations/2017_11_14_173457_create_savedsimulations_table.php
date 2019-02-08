<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedsimulationsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('savedsimulations', function (Blueprint $table) {
            $table->increments('saved_sim_id');
            $table->string('pathogen_name');
            $table->string('food_name');
            $table->integer('temp');
            $table->integer('time');
            $table->integer('cells');
            $table->string('simulation_name');
            $table->integer('infectious_dosage');
            $table->integer('doubling_time');
            $table->string('img');
            $table->double('growth_rate');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('savedsimulations');
    }
}
