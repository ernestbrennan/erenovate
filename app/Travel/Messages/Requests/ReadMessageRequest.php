<?php

namespace App\Travel\Messages\Requests;

use App\Travel\Base\BaseFormRequest;

class ReadMessageRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:guarantee_message,id',
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