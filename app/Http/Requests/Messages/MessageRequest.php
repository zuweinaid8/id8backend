<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'body' => 'required|string',
            ];
        }

        return [
            'body' => 'nullable|required_without:attachment_file_id|string',
            'attachment_file_id' => 'bail|nullable|integer|exists:files,id',
        ];
    }
}
