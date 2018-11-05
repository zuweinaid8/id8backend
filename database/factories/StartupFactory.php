<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Startup::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'year_founded' => $faker->year,
        'physical_address' => [
            'line1' => $faker->streetAddress,
            'line2' => $faker->streetName,
            'city' => $faker->city,
            'state' => $faker->city,
            'country' => $faker->country,
        ],
        'is_registered' => $faker->boolean,
        'web_address' => $faker->url,
        'description' => $faker->text,
        'business_licence_no' => strtoupper($faker->bothify('??-###?####?')),
        'tin_no' => strtoupper($faker->bothify('??#-###-#####')),
        'owner_id' => function () {
            return factory(\App\User::class)->create(['type'=>'startup'])->id;
        },
    ];
});

$factory->afterCreating(\App\Models\Startup::class, function ($startup, $faker) {
    factory(\App\Models\Solution::class)->create([
        'startup_id' => $startup->id,
    ]);
});
