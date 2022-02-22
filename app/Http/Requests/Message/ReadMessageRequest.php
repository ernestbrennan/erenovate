<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class ReadMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message_id' => 'required|numeric|exists:guarantee_message,id',
        ];
    }

    public function messages()
    {
        return [
            'message_id.required' => trans('validation.required'),
            'message_id.numeric' => trans('validation.numeric'),
            'message_id.exists' => trans('validation.exists'),
        ];
    }

}