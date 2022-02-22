<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'guarantee_project_id' => 'required|numeric|exists:guarantee_project,id',
            'content' => 'required|string',
            'files' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'guarantee_project_id.required' => trans('validation.required'),
            'guarantee_project_id.numeric' => trans('validation.numeric'),
            'guarantee_project_id.exists' => trans('validation.exists'),

            'content.required' => trans('validation.required'),
            'content.string' => trans('validation.string'),
        ];
    }
}