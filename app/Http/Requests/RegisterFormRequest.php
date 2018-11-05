<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
        $request = [
            'name' => 'required|string',
            'name_of_fintech' => 'required|string',
            'year_founded' => 'required|numeric|digits:4',
            'email' => 'required|email|unique:users,email',
            'profile_picture_file_id' => 'nullable|exists:files,id',
        ];

        if (request()->path() == 'api/register/mentor') {
            $request['meta'] ='bail|required';
            $request['meta.default_password'] ='bail|required|min:8';
            $request['meta.mentor_ship_areas'] = 'bail|required|array|distinct|max:2';
            $request['meta.mentor_ship_areas.*'] = 'bail|required|integer|exists:mentor_ship_areas,id';
            return $request;
        } elseif (request()->path() == 'api/user' && request()->isMethod('POST')) {
            unset($request['email']);
            return $request;
        } else {
            $request['password'] = 'bail|required|min:8';
            return $request;
        }
    }
}
