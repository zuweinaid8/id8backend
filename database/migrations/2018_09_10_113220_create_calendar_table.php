<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('owner_id')->nullable();

            $table->string('name');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('calendar_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id');

            $table->boolean('is_public')->nullable();
            $table->boolean('notifications_enabled')->nullable();
            $table->string('timezone')->nullable();
            $table->string('location')->nullable();

            $table->primary('calendar_id');

            $table->foreign('calendar_id')
                ->references('id')
                ->on('calendars')
                ->onDelete('cascade');
        });

        Schema::create('calendar_activities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('type')->comments('Activity type: event, task, call');
            $table->unsignedBigInteger('calendar_id');
            $table->unsignedBigInteger('owner_id');

            $table->string('title');
            $table->text('description')->nullable();

            $table->dateTimeTz('start_at')->nullable()->comments('Start date applicable to EVENT type activities');
            $table->dateTimeTz('end_at')->nullable()->comments('End date applicable to EVENT type activities');

            $table->dateTimeTz('due_at')->nullable()->comments('Due date used with TASK type activities');

            $table->string('location')->nullable();

            $table->char('color_id', 6)->nullable()->comment('Default color: #616164 - gray shade');

            $table->jsonb('meta')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('type');

            $table->foreign('calendar_id')->references('id')
                ->on('calendars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_activities');
        Schema::dropIfExists('calendar_settings');
        Schema::dropIfExists('calendars');
    }
}
