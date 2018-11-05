<?php

namespace App\Http\Requests\Assignments;

use App\Models\Assignments\AssignmentSubmission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class AssignmentSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize()
    {
        if (! $this->user()->isStartup()) {
            throw new AuthorizationException('Unauthorized! Only startups can CREATE or UPDATE submissions');
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
            'online_text' => 'required_without:files|string|',
            'files' => 'nullable|array|',
            'files.*.id' => 'bail|nullable|integer|exists:files,id',
        ];
    }
}
