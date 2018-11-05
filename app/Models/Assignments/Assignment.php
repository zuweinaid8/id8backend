<?php

namespace App\Models\Assignments;

use App\Exceptions\BaseException;
use App\Models\BaseModel as Model;
use App\Models\SolutionMentorship;
use App\Traits\AuditsUserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes, AuditsUserTrait;

    protected $fillable = [
        'mentorship_id',
        'title',
        'description',
    ];

    protected $casts = [
        'mentorship_id' => 'integer',
        'meta' => 'object',
    ];

    protected $attributes = [
        'meta' => '{}',
    ];

    public static function boot()
    {
        // Bind to company scope
        static::addGlobalScope('view', function (Builder $builder) {
            $user = request()->user();
            switch ($user->type) {
                case 'admin':
                    break;

                case 'mentor':
                    $builder->whereHas('mentorship', function ($q) use ($user) {
                        return $q->where('mentor_id', '=', $user->id);
                    });
                    break;

                case 'startup':
                    $builder->whereHas('mentorship', function ($q) use ($user) {
                        return $q->whereHas('solution', function ($q) use ($user) {
                            return $q->whereHas('startup', function ($q) use ($user) {
                                return $q->where('owner_id', '=', $user->id);
                            });
                        });
                    });
                    break;

                default:
                    throw new BaseException('Assignment scope not available for role: ' . $user->type);
                    break;
            }
        });

        parent::boot();
    }

    public function mentorship()
    {
        return $this->belongsTo(SolutionMentorship::class);
    }

    public function settings()
    {
        return $this->hasOne(AssignmentSetting::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function grades()
    {
        return $this->hasMany(AssignmentGrade::class);
    }
}
