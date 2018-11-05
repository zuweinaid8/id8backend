<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\SolutionMentorship::class, function (Faker $faker) {
    $mentor = \App\Models\Mentor::query()->inRandomOrder()->first();
    return [
        'mentor_id' => function () use ($mentor) {
            return $mentor->id;
        },
        'solution_id' => function () {
            return \App\Models\Solution::query()->inRandomOrder()->first()->id;
        },
        'mentor_ship_area_id' => function () use ($mentor) {
            return $mentor->mentor_areas()->inRandomOrder()->first()->id;
        },
    ];
});
