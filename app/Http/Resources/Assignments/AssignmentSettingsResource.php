<?php

namespace App\Http\Resources\Assignments;

use App\Http\Resources\BaseResource;

class AssignmentSettingsResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'start_date_enabled' => (bool)$this->start_date_enabled,
            'start_date' => $this->start_date,
            'due_date_enabled' => (bool)$this->due_date_enabled,
            'due_date' => $this->due_date,
            'cut_off_date_enabled' => (bool)$this->cut_off_date_enabled,
            'cut_off_date' => $this->cut_off_date,
            'grading_due_date_enabled' => (bool)$this->grading_due_date_enabled,
            'grading_due_date' => $this->grading_due_date,
            'online_text_enabled' => (bool)$this->online_text_enabled,
            'online_text_word_limit' => (int)$this->online_text_word_limit,
            'file_submission_enabled' => (bool)$this->file_submission_enabled,
            'max_uploaded_files' => (int)$this->max_uploaded_files,
            'max_submission_size' => (int)$this->max_submission_size,
            'notifications_enabled' => (bool)$this->notifications_enabled,
        ];
    }
}
