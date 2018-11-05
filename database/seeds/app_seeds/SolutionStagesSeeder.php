<?php

use App\Models\SolutionStage;
use Illuminate\Database\Seeder;

class SolutionStagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            [
                'sequence' => 1,
                'code' => 'submitted',
                'name' => 'Submitted',
                'description' => 'Your Solution has been submitted successfully',
            ],
            [
                'sequence' => 2,
                'code' => 'idea',
                'name' => 'Idea',
                'description' => 'Your solution is an idea and is understood as a basic element of thought that can be either visual, concrete, or abstract.',
            ],
            [
                'sequence' => 3,
                'code' => 'development',
                'name' => 'Development',
                'description' => 'Your concept is put into a concrete form. You have a working prototype.',
            ],
            [
                'sequence' => 4,
                'code' => 'pilot',
                'name' => 'Pilot',
                'description' => 'This stage involves a test market that is as close to a real market situation as possible.',
            ],
            [
                'sequence' => 5,
                'code' => 'scale',
                'name' => 'Scale',
                'description' => 'You have a successful product launch and are looking for opportunities for growth.',
            ],
        ];

        foreach ($stages as $stage) {
            SolutionStage::query()->create($stage);
        }
    }
}
