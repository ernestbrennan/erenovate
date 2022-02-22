<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;


class DownloadFileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file_id' => 'required|exists:guarantee_file,id',
        ];
    }

    public function messages()
    {
        return [
            'file.file_id' => trans('validation.required'),
            'file.exists' => trans('validation.exists'),
        ];
    }
}