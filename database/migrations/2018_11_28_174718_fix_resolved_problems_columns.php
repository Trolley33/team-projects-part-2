<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixResolvedProblemsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resolved_problems', function (Blueprint $table) 
        {
            $table->renameColumn('problem_id', 'id');
            //$table->integer('problem_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resolved_problems', function (Blueprint $table) 
        {
            $table->renameColumn('id', 'problem_id');
            //$table->integer('problem_id');
        });
    }
}
