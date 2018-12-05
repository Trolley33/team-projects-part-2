<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HardwareSoftwareChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn('affects');
        });

        Schema::create('affected_hardware', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('problem_id');
            $table->integer('equipment_id');
            $table->timestamps();
        });

        Schema::create('affected_software', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('problem_id');
            $table->integer('software_id');
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
        Schema::table('problems', function (Blueprint $table) {
            $table->integer('affects');
        });
        
        Schema::dropIfExists('affected_hardware');
        Schema::dropIfExists('affected_software');

    }
}
