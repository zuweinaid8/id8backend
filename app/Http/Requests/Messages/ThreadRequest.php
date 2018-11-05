<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class ThreadRequest extends FormRequest
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
            'title' => 'bail|required|string|unique:threads,title',
            'description' => 'nullable|string',
            'type' => 'nullable|in:one2one,group,public',
            'participants' => 'required|array|min:1',
            'participants.*.user_id' => 'bail|required|integer|exists:users,id',

            // optional message object
            'message.body' => 'nullable|string',
            'message.attachment_file_id' => 'bail|nullable|integer|exists:files,id',
        ];
    }
}
