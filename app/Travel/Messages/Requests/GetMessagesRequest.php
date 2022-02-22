<?php

namespace App\Travel\Messages\Requests;

use App\Travel\Base\BaseFormRequest;

class GetMessagesRequest extends BaseFormRequest
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
            'offset.numeric' => trans('validation.numeric'),
            'per_page.numeric' => trans('validation.numeric'),
        ];
    }
}