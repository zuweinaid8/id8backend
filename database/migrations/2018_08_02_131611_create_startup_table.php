<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStartupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('startups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->year('year_founded');
            $table->json('physical_address');
            $table->boolean('is_registered')->default(false);
            $table->text('web_address');
            $table->text('description');
            $table->string('business_licence_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->unsignedBigInteger('owner_id');

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('startups');
    }
}
