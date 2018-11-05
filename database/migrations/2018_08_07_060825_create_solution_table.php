<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->text('description');
            $table->text('link');
            $table->json('team');

            $table->unsignedBigInteger('startup_id');
            $table->unsignedBigInteger('stage_id');
            $table->unsignedBigInteger('hurdle_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();

            $table->unsignedBigInteger('pitch_deck_file_id')->nullable();
            $table->unsignedBigInteger('business_model_file_id')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('stage_id')
                ->references('id')
                ->on('solution_stages')
                ->onDelete('restrict');

            $table->foreign('status_id')
                ->references('id')
                ->on('solution_statuses')
                ->onDelete('restrict');

            $table->foreign('hurdle_id')
                ->references('id')
                ->on('hurdles')
                ->onDelete('restrict');

            $table->foreign('startup_id')
                ->references('id')
                ->on('startups')
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
        Schema::dropIfExists('solutions');
    }
}
