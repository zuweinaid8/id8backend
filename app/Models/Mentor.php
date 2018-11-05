<?php

namespace App\Models;

use App\User;

class Mentor extends User
{
    protected $hidden = ['pivot', 'password'];

    protected $with = [
        'profile_photo_file'
    ];

    protected $attributes = [
        'meta' => '{}',
        'type' => 'mentor',
    ];

    public function mentor_areas()
    {
        return $this->belongsToMany('\App\Models\MentorshipArea', 'mentor_with_areas', 'mentor_id', 'area_id');
    }

    public function solutions()
    {
        return $this->belongsToMany('\App\Models\Solution', 'solution_mentors', 'mentor_id', 'solution_id');
    }

    public function mentorships()
    {
        return $this->hasMany('\App\Models\SolutionMentorship', 'mentor_id', 'id');
    }

    public function scopeMentor($query)
    {
        return $query->where('type', 'mentor');
    }
}
