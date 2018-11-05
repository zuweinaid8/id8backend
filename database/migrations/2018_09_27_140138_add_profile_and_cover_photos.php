<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileAndCoverPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->unsignedInteger('cover_photo_file_id')->nullable();
            $table->unsignedInteger('logo_file_id')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('cover_photo_file_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->dropColumn('cover_photo_file_id');
            $table->dropColumn('logo_file_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cover_photo_file_id');
        });
    }
}
