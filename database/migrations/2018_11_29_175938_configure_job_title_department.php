<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConfigureJobTitleDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
        });

        Schema::create('jobs', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('department_id');
            $table->string('title');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('job_title');
            $table->dropColumn('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('jobs');

        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title');
            $table->string('department');
        });
    }
}
