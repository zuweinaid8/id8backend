<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->string('mime_type')->nullable();
            $table->double('size')->comment('Size of file in bytes');
            $table->string('extension')->comment('File extension');
            $table->text('notes')->nullable();
            $table->string('disk');
            $table->string('path');
            $table->boolean('is_public');
            $table->jsonb('meta')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });

        Schema::create('file_links', function (Blueprint $table) {
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('file_link_id');
            $table->string('file_link_type');
            $table->timestampsTz();

            $table->primary(['file_id', 'file_link_id', 'file_link_type']);

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
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
        Schema::dropIfExists('file_links');
        Schema::dropIfExists('files');
    }
}
