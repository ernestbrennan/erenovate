<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class GetListRequest extends FormRequest
{
    public function rules()
    {
        return [
            'offset' => 'numeric',
            'per_page' => 'numeric'
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