<?php

namespace App\Travel\Messages\Requests;

use App\Travel\Base\BaseFormRequest;

class SendMessageRequest extends BaseFormRequest
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