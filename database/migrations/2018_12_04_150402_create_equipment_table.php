<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->integer('serial_number')->unique();
            $table->string('description');
            $table->string('model');
            $table->timeStamps();
        });

        Schema::create('software', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timeStamps();
        });


        Schema::create('speciality', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('specialist_id');
            $table->integer('problem_type_id');
            $table->timeStamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('software');
        Schema::dropIfExists('speciality');
    }
}
