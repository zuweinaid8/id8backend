<?php
/**
 * Created by IntelliJ IDEA.
 * User: macelo
 * Date: 08/08/2018
 * Time: 11:14
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionMentorFormRequest extends FormRequest
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
            'solution_id' => 'bail|required|exists:solutions,id',
            'mentor_ship_area_id' => 'bail|required|exists:mentor_ship_areas,id',
        ];
    }
}
