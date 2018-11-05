<?php

namespace App\Http\Requests\Calendars;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize()
    {
        if (! $this->user()->isAdmin()) {
            throw new AuthorizationException('Unauthorized! Only Admins can create events on calendar.');
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
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'location' => 'nullable|string',
            'participants' => 'nullable',
            'participants.admins' => 'nullable|boolean',
            'participants.mentors' => 'nullable|boolean',
            'participants.startups' => 'nullable|boolean',
            'participants.investors' => 'nullable|boolean',
            'color_id' => [
                'nullable',
                'string',
                //'regex:/([0-9A-Fa-f]){6}\b/'
            ],
            'meta' => 'nullable',
        ];
    }
}
