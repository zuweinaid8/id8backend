<?php

namespace App\Http\Requests;

use App\Commons\MentorOldPasswordValidator;
use App\Commons\Utils;
use Illuminate\Foundation\Http\FormRequest;

class UserPasswordResetFormRequest extends FormRequest
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
            'password' => 'bail|required|confirmed|min:8',
            'old_password'=> array('bail','required', new MentorOldPasswordValidator())
        ];
    }
}
