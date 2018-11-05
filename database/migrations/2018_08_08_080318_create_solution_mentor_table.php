<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionMentorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_mentors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('solution_id');
            $table->unsignedBigInteger('mentor_id');
            $table->unsignedBigInteger('mentor_ship_area_id');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('mentor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('solution_id')
                ->references('id')
                ->on('solutions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solution_mentors');
    }
}
