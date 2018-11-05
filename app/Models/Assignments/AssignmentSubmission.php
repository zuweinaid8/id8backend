<?php

namespace App\Models\Assignments;

use App\Models\BaseModel as Model;
use App\Models\Files\File;
use App\Traits\AuditsUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use SoftDeletes, AuditsUserTrait;

    protected $fillable = [
        'online_text',
    ];

    protected $attributes = [
        'attempt_number' => 1,
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'file_link');
    }
}
