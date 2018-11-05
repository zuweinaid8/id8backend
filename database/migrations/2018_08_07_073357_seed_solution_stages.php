<?php

use App\Models\SolutionStage;
use App\User;
use Illuminate\Database\Migrations\Migration;

class SeedSolutionStages extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'SolutionStagesSeeder',
            '--force' => true,
        ]);

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'AdminUserSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SolutionStage::query()->delete();
    }
}
