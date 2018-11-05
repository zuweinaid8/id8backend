<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('file_id');
            $table->enum('type', ['user', 'role', 'public']);
            $table->string('access')->comment('Access: owner, writer, or reader');

            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->comment('Applicable when type is `user`');
            $table->enum('role_id', ['admin', 'startup', 'mentor', 'investor'])
                ->nullable()
                ->comment('Applicable when type is `role`');

            $table->timestampsTz();

            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_permissions');
    }
}
