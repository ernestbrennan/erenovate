<?php

namespace App\Http\Requests\Issue;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'issue_id' => 'required|numeric|exists:guarantee_issue,id',
            'content' => 'required|string',
            'files' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'issue_id.required' => trans('validation.required'),
            'issue_id.numeric' => trans('validation.numeric'),
            'issue_id.exists' => trans('validation.exists'),

            'content.required' => trans('validation.required'),
            'content.string' => trans('validation.string'),
        ];
    }
}