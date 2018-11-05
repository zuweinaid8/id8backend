<?php

use App\Models\MentorshipArea;
use Illuminate\Database\Migrations\Migration;

class SeedMentorAreas extends Migration
{
    private $mentor_areas = [
        [
            'name' => 'Product',
            'description' => 'Product',
        ],
        [
            'name' => 'Development',
            'description' => 'Development',
        ],
        [
            'name' => 'Story telling',
            'description' => 'Story telling',
        ],
        [
            'name' => 'Profit and loss',
            'description' => 'Profit and loss',
        ],
        [
            'name' => 'Go to market',
            'description' => 'Go to market',
        ],
        [
            'name' => 'Team',
            'description' => 'Team',
        ],
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->mentor_areas as $area) {
            MentorshipArea::query()->create($area);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        MentorshipArea::query()->delete();
    }
}
