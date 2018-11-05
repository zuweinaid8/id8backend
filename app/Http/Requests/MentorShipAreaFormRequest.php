<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MentorShipAreaFormRequest extends FormRequest
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
        $request =  [
            'name' => 'bail|required|string|unique:mentor_ship_areas,name',
            'description' => 'bail|required|string',
        ];
        $delete_req =  [
            'mentor_ship_area_id' => 'bail|required|exists:mentor_ship_areas,id',
        ];

        if (request()->isMethod('PUT')) {
            return $delete_req;
        } else {
            return $request;
        }
    }
}
