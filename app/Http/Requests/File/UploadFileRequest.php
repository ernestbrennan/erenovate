<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function rules()
    {
        return [
//            'file' => 'required|mimes:pdf,png',
        ];
    }

    public function messages()
    {
        return [
            'file.mimes' => trans('validation.mimes'),
        ];
    }
}