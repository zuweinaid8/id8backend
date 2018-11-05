<?php

namespace App\Http\Requests;

use App\Commons\YearRangeValidator;
use Illuminate\Foundation\Http\FormRequest;

class StartupFormRequest extends FormRequest
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
        if($this->isMethod('PUT')){
            $id = $this->route('startup');
        }
        else {
            $id = 'NULL';
        }

        return [
            'name' => "required|string|unique:startups,name,$id",
            'year_founded' =>  array('required', new YearRangeValidator('1950')),
            'is_registered' => 'bail|required|boolean',

            'physical_address' => 'nullable',
            'physical_address.line1' => 'nullable|string',
            'physical_address.line2' => 'nullable|string',
            'physical_address.city' => 'nullable|string',
            'physical_address.state' => 'nullable|string',
            'physical_address.country' => 'nullable|string',

            'web_address' => 'nullable|string',

            'description' => 'nullable|string',
            'business_licence_no' => 'nullable|required_if:is_registered,==,true|string',
            'tin_no' => 'nullable|required_if:is_registered,==,true,string',
        ];
    }
}
