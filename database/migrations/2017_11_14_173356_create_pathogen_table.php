<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathogenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathogen', function (Blueprint $table) {
            $table->increments('path_id');
            $table->string('pathogen_name');
            $table->string('formula');
            $table->integer('low_temp');
            $table->integer('mid_temp');
            $table->integer('high_temp');
            $table->string('image');
            $table->string('desc_link');
            $table->integer('infectious_dose');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pathogen');
    }
}
