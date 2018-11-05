<?php

namespace App\Http\Requests\Calendars;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => 'bail|required|string|',
            'description' => 'nullable|string|',
            'settings' => 'nullable|',
            'settings.is_public' => 'nullable|boolean',
            'settings.notifications_enabled' => 'nullable|boolean',
            'settings.timezone' => 'nullable|string',
            'settings.location' => 'nullable|string',
        ];
    }
}
