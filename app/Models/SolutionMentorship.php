<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SolutionMentorship extends BaseModel
{
    use SoftDeletes;

    protected $hidden = [
        'solution_id',
        'mentor_id',
        'mentor_ship_area_id',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $with = [
        'mentor',
        'mentorship_area',
    ];

    protected $table = 'solution_mentors';

    public function mentor()
    {
        return $this->hasOne('\App\Models\Mentor', 'id', 'mentor_id');
    }

    public function mentorship_area()
    {
        return $this->hasOne('\App\Models\MentorshipArea', 'id', 'mentor_ship_area_id');
    }

    public function solution()
    {
        return $this->hasOne(Solution::class, 'id', 'solution_id');
    }
}
