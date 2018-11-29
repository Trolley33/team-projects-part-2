<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToJobDepartmentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            
            $table->timestamps();
        });
        Schema::table('departments', function (Blueprint $table) {
            
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
        Schema::table('jobs', function (Blueprint $table) {
            
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('departments', function (Blueprint $table) {
            
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
