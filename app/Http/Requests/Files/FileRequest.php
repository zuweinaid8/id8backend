<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Increase max upload size limits
        ini_set('post_max_size', '200M');
        ini_set('upload_max_filesize', '200M');

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
                'notes' => 'nullable|string',
                'name' => 'nullable|string|regex:#^.*\.[^\\$]#i', // Regex checks filename has extension
                'meta' => 'nullable|array',
            ];
        }

        return [
            'document' => [
                'required',
                'file',
                'max:128000', // limit size to 128 MB

                /**
                 * Mimes
                 * ai	application/postscript
                 * csv	text/csv
                 * doc	application/msword
                 * docx	application/vnd . openxmlformats - officedocument . wordprocessingml . document
                 * eps	application/postscript
                 * gif	image/gif
                 * jpeg	image/jpeg
                 * jpg	image/jpg
                 * ods	application/vnd . oasis . opendocument . spreadsheet
                 * pdf	application/pdf
                 * png	image/png
                 * rtf	text/rtf
                 * tif	image/tiff
                 * txt	text/plain
                 * xls	application/vnd . ms - excel
                 * xlsx	application/vnd . openxmlformats - officedocument . spreadsheetml . sheet
                 * xml	text/xml
                 * mp4  video/mp4
                 */
                'mimes:ai,eps,gif,ods,rtf,tif,xml,png,jpeg,jpg,pdf,xls,xlsx,doc,docx,txt,csv,mp4',
            ],
            'notes' => 'nullable|string',
            'name' => 'nullable|string',
            'meta' => 'nullable|array',
        ];
    }
}
