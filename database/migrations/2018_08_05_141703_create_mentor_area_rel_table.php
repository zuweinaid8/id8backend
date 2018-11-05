<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMentorAreaRelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentor_with_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mentor_id');
            $table->unsignedBigInteger('area_id');
            $table->timestampsTz();

            $table->foreign('mentor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('area_id')
                ->references('id')
                ->on('mentor_ship_areas')
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
        Schema::dropIfExists('mentor_with_areas');
    }
}
