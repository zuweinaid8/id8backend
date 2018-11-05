<?php

namespace App\Http\Requests\Assignments;

use App\Models\SolutionMentorship;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class AssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize()
    {
        $user = $this->user();

        // Check if user is a mentor
        if ($user->type !== 'mentor') {
            throw new AuthorizationException('Sorry! Only mentors are allowed to create or update assignments');
        }

        // Validate mentorship id is numeric to avoid db exception on query
        if (!is_numeric($mentorship_id = $this->input('mentorship_id'))) {
            logger('Invalid input for mentorship_id supplied');
            throw new AuthorizationException('Invalid input supplied for mentorship_id');
        }

        // Check mentorship belongs to current mentor
        $mentorship = SolutionMentorship::query()->findOrNew($mentorship_id);

        if (! ($mentorship->mentor_id == $user->id)) {
            throw new AuthorizationException('Sorry! You can only create or update assignments on mentorships assigned to you');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mentorship_id' => 'bail|required|numeric|exists:solution_mentors,id,deleted_at,NULL',

            'title' => 'required|string',
            'description' => 'required|string',

            'settings' => 'nullable|',

            'settings.start_date_enabled' => 'nullable|boolean',
            'settings.start_date' => 'required_if:"settings.start_date_enabled",==,true|date|date_format:"Y-m-d H:i"',

            'settings.due_date_enabled' => 'nullable|boolean',
            'settings.due_date' => 'required_if:"settings.due_date_enabled",==,true|date|date_format:"Y-m-d H:i"',

            'settings.cut_off_date_enabled' => 'nullable|boolean',
            'settings.cut_off_date' => 'required_if:"settings.cut_off_date_enabled",==,true|date|date_format:"Y-m-d H:i"',

            'settings.grading_due_date_enabled' => 'nullable|boolean',
            'settings.grading_due_date' => 'required_if:"settings.grading_due_date_enabled",==,true|date|date_format:"Y-m-d H:i"',

            'settings.online_text_enabled' => 'nullable|boolean',
            'settings.online_text_word_limit' => 'required_if:"settings.online_text_enabled",==,true|integer',

            'settings.file_submission_enabled' => 'nullable|boolean',
            'settings.max_uploaded_files' => 'required_if:"settings.file_submission_enabled",==,true|integer',
            'settings.max_submission_size' => 'required_if:"settings.file_submission_enabled",==,true|integer',

            'settings.notifications_enabled' => 'nullable|boolean',
        ];
    }
}
