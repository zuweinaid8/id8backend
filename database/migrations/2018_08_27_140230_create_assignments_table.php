<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mentorship_id');
            $table->string('title');
            $table->string('description');

            $table->jsonb('meta')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['mentorship_id'], 'ix_assignment_mentorship');
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedInteger('attempt_number');

            $table->text('online_text')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('assignment_id')->references('id')
                ->on('assignments')->onDelete('cascade');
        });

        Schema::create('assignment_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedInteger('attempt_number');
            $table->double('points');
            $table->text('feedback_comments')->nullable();

            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('assignment_id')->references('id')
                ->on('assignments')->onDelete('cascade');
        });

        Schema::create('assignment_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('assignment_id');

            $table->boolean('start_date_enabled');
            $table->timestampTz('start_date')->nullable();

            $table->boolean('due_date_enabled');
            $table->timestampTz('due_date')->nullable();

            $table->boolean('cut_off_date_enabled');
            $table->timestampTz('cut_off_date')->nullable();

            $table->boolean('grading_due_date_enabled');
            $table->timestampTz('grading_due_date')->nullable();

            $table->boolean('online_text_enabled');
            $table->unsignedInteger('online_text_word_limit')->nullable();

            $table->boolean('file_submission_enabled');
            $table->unsignedInteger('max_uploaded_files')->nullable();
            $table->unsignedInteger('max_submission_size')->nullable();
            //$table->string('accepted_file_types')->nullable();

            $table->boolean('notifications_enabled');

            $table->timestampsTz();

            $table->primary(['assignment_id']);

            $table->foreign('assignment_id')
                ->references('id')
                ->on('assignments')
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
        Schema::dropIfExists('assignment_settings');
        Schema::dropIfExists('assignment_grades');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }
}
