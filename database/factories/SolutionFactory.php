<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Solution::class, function (Faker $faker) {

    //Prepare team member data
    $team = [];
    for ($i = 1; $i <= $faker->numberBetween(1, 5); $i++) {
        $team[] = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->companyEmail,
            'position' => $faker->randomElement(['CTO', 'CFO', 'Developer', 'Data Scientist', 'Team Lead', 'Analyst']),
            'gender' => $faker->randomElement(['male', 'female']),
        ];
    }

    return [

        'hurdle_id' => function () {
            return \App\Models\Hurdle::query()->active(now())->first()->id;
        },
        'startup_id' => function () {
            return factory(\App\Models\Startup::class)->create()->id;
        },
        'status_id' => function () {
            return \App\Models\SolutionStatus::query()->inRandomOrder()->first()->id;
        },
        'stage_id'=> function () {
            return \App\Models\SolutionStage::query()->inRandomOrder()->first()->id;
        },

        'title' => $faker->company,
        'description' => $faker->sentence,
        'link' => $faker->url,
        'team' => $team,
//        'pitch_deck_file_id',
//        'business_model_file_id',
    ];
});
