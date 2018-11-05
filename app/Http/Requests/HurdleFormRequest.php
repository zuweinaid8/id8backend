<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HurdleFormRequest extends FormRequest
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
            'name' => 'bail|required|string',
            'description' => 'bail|required|string',
            'from' => 'bail|required|date_format:Y-m-d',
            'to' => 'bail|required|date_format:Y-m-d|after:from',
        ];
    }
}
