<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Calendars\Calendar::class, function (Faker $faker) {
    $admin = \App\User::query()->where('type', '=', 'admin')->first();
    return [
        'owner_id' => $admin->id,
        'name' => $faker->word,
        'description' => $faker->sentence,
        'created_by_id' => $admin->id,
        'updated_by_id' => $admin->id,
    ];
});

$factory->afterCreating(App\Models\Calendars\Calendar::class, function ($calendar, $faker) {
    $calendar->settings()->create([
        'is_public' => true,
        'notifications_enabled' => true,
        'timezone' => 'UTC',
        'location' => '',
    ]);
});
