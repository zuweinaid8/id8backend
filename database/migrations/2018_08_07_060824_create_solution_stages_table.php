<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_stages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->unique();
            $table->unsignedTinyInteger('sequence')->nullable();

            $table->string('name');
            $table->text('description');

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solution_stages');
    }
}
