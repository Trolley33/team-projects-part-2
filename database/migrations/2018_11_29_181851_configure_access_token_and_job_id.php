<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConfigureAccessTokenAndJobId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->integer('access_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('type');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('access_level');
        });
    }
}
