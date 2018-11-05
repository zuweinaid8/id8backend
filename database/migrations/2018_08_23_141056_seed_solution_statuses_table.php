<?php

use App\Models\SolutionStatus;
use Illuminate\Database\Migrations\Migration;

class SeedSolutionStatusesTable extends Migration
{
    private $statuses = [
        [
            'name' => 'Under Review',
            'description' => 'Your solution is an idea and is understood as a basic element of thought that can be either visual, concrete, or abstract.',
        ],
        [
            'name' => 'Rejected',
            'description' => 'Your solution can not proceed further',
        ],
        [
            'name' => 'Passed',
            'description' => 'This status involves a test market that is as close to a real market situation as possible.',
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->statuses as $status) {
            SolutionStatus::query()->create($status);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SolutionStatus::query()->delete();
    }
}
