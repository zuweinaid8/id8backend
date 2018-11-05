<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MentorFormRequest extends FormRequest
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
        return  [
            'name' => 'nullable|filled|string',
            'email' => 'nullable|filled|email|unique:users,email',
            'profile_picture_file_id' => 'nullable|filled|exists:files,id',
            'meta' => 'nullable',
            'meta.default_password' =>'bail|filled|nullable|min:8',
            'meta.mentor_ship_areas' => 'bail|filled|nullable|array|distinct|max:2',
            'meta.mentor_ship_areas.*' => 'bail|filled|nullable|integer|exists:mentor_ship_areas,id'

        ];
    }
}
