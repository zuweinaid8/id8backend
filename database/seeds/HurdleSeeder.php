<?php

use Illuminate\Database\Seeder;

class HurdleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Hurdle::create([
            'name' => 'The Hurdle - ' . now()->year,
            'from' => now()->subMonth(),
            'to' => now()->addMonths(2),
        ]);
    }
}
