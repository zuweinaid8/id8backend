<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionFormRequest extends FormRequest
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
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'team' => 'nullable',
            'team.*.first_name' => 'required|string',
            'team.*.last_name' => 'required|string',
            'team.*.position' => 'required|string',
            'team.*.email' => 'required|email',
            'team.*.gender' => 'required|in:male,female',

            'stage_id' => 'bail|required|integer|exists:solution_stages,id',
          //  'status_id' => 'bail|required|integer|exists:solution_statuses,id',

            'pitch_deck_file_id' => 'bail|required|integer|exists:files,id,deleted_at,NULL',
            'business_model_file_id' => 'bail|required|integer|exists:files,id,deleted_at,NULL',
            'cover_photo_file_id' => 'bail|nullable|integer|exists:files,id,deleted_at,NULL',
            'video_file_id' => 'bail|nullable|integer|exists:files,id,deleted_at,NULL',
        ];
        if(request()->isMethod('PUT')){
        }else{
            $request['title'] = 'required|string|unique:solutions,title';
        }
      return $request;
    }
}
