<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class GetMessagesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'chat_id' => 'required|numeric|exists:guarantee_chat_room,id',
            'offset' => 'numeric',
            'per_page' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'guarantee_project_hash.required' => trans('validation.required'),
            'guarantee_project_hash.numeric' => trans('validation.numeric'),
            'guarantee_project_hash.exists' => trans('validation.exists'),

            'offset.numeric' => trans('validation.numeric'),
            'per_page.numeric' => trans('validation.numeric'),
        ];
    }
}