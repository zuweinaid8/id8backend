<?php

namespace App\Models\Assignments;

use App\Models\BaseModel as Model;

class AssignmentSetting extends Model
{
    public $incrementing = false;

    protected $touches = [
        'assignment',
    ];

    protected $fillable = [
        'start_date_enabled',
        'start_date',
        'due_date_enabled',
        'due_date',
        'cut_off_date_enabled',
        'cut_off_date',
        'grading_due_date_enabled',
        'online_text_enabled',
        'online_text_word_limit',
        'file_submission_enabled',
        'max_uploaded_files',
        'max_submission_size',
        'notifications_enabled',
    ];

    protected $casts = [
        'start_date_enabled' => 'boolean',
        'start_date' => 'date',
        'due_date_enabled' => 'boolean',
        'due_date' => 'date',
        'cut_off_date_enabled' => 'boolean',
        'cut_off_date' => 'date',
        'grading_due_date_enabled' => 'boolean',
        'online_text_enabled' => 'boolean',
        'file_submission_enabled' => 'boolean',
        'notifications_enabled' => 'boolean',
        'max_uploaded_files' => 'integer',
        'max_submission_size' => 'integer',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
