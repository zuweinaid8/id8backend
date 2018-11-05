<?php

namespace App\Models;

use App\Models\Files\File;

class Solution extends BaseModel
{
    protected $fillable = [
        'description',
         'link',
         'team',
          'title',
         'start_up_id',
         'status_id',
         'stage_id',
         'hurdle_id',
         'pitch_deck_file_id',
         'cover_photo_file_id',
         'business_model_file_id',
         'video_file_id',
    ];
    protected $casts = [
        'team' => 'object'
    ];

    protected $attributes = [
        'team' => '[]',
    ];

    protected $hidden = ['pivot'];

    public function stage()
    {
        return $this->hasOne('\App\Models\SolutionStage', 'id', 'stage_id');
    }

    public function status()
    {
        return $this->hasOne('\App\Models\SolutionStatus', 'id', 'status_id');
    }

    public function mentor_areas()
    {
        return $this->belongsToMany('\App\Models\MentorshipArea', 'solution_mentor_ship_area', 'solution_id', 'area_id');
    }

    public function mentors()
    {
        return $this->belongsToMany('\App\Models\Mentor', 'solution_mentors', 'solution_id', 'mentor_id');
    }

    public function mentorships()
    {
        return $this->hasMany('\App\Models\SolutionMentorship', 'solution_id', 'id');
    }

    public function startup()
    {
        return $this->belongsTo('\App\Models\Startup', 'startup_id', 'id');
    }

    public function pitch_deck_file()
    {
        return $this->belongsTo(File::class, 'pitch_deck_file_id', 'id');
    }

    public function business_model_file()
    {
        return $this->belongsTo(File::class, 'business_model_file_id', 'id');
    }

    public function video_file()
    {
        return $this->belongsTo(File::class, 'video_file_id', 'id');
    }

    public function cover_photo_file()
    {
        return $this->belongsTo(File::class, 'cover_photo_file_id', 'id')
            ->withDefault([
                'mime_type' => 'image/jpeg',
                'url' => 'https://id8-assets.s3-eu-west-1.amazonaws.com/images/ID8_HEADER.jpeg',
            ]);
    }

    public function hurdle()
    {
        return $this->belongsTo(Hurdle::class, 'hurdle_id', 'id');
    }

    public function configureViewQuery($query)
    {
        return $query->whereHas('startup', function ($q) {
            $q->where('owner_id', request()->user()->id);
        })->orWhereHas('mentors', function ($q) {
            $q->where('users.id', request()->user()->id);
        });
    }
}
