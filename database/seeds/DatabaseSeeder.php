<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->initialize();

        $this->call([
            AdminUserSeeder::class,
            HurdleSeeder::class,
            UsersTableSeeder::class,
        ]);
    }

    /**
     * @return $this
     */
    public function initialize()
    {
        $this->command->info('Unguarding models');

        \Illuminate\Database\Eloquent\Model::unguard();

        $tables = [
            'solutions',
            'startups',
            'hurdles',
            'users',
        ];

        $this->command->info('Truncating existing tables');

        foreach ($tables as $table) {
            DB::table($table)->delete();
        }

        return $this;
    }
}
