<?php

namespace App\Models\Assignments;

use App\Models\BaseModel as Model;
use App\User;
use App\Traits\AuditsUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentGrade extends Model
{
    use SoftDeletes, AuditsUserTrait;

    protected $fillable = [
        'points',
        'feedback_comments',
    ];

    protected $attributes = [
        'attempt_number' => 1,
    ];

    protected $casts = [
        'assignment_id' => 'integer',
        'attempt_number' => 'integer',
        'points' => 'double'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function graded_by()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
