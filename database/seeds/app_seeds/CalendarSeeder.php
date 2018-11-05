<?php

use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Calendars\Calendar::class)->create([
            'name' => 'ID8 Calendar (default)',
            'description' => 'ID8 Calendar (default)',
        ]);
    }
}
