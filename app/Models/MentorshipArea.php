<?php

namespace App\Models;

class MentorshipArea extends BaseModel
{
    protected $table = 'mentor_ship_areas';
    protected $hidden = array('pivot');
    protected $fillable = [
        'name',
        'description'
    ];

    public function mentors()
    {
        return $this->belongsToMany('\App\Models\Mentor');
    }
    public function solutions()
    {
        return $this->belongsToMany('\App\Models\Solution');
    }

    public function configureViewQuery($query)
    {
        // TODO: Implement configureViewQuery() method.
    }
}
