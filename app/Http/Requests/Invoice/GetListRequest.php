<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class GetListRequest extends FormRequest
{
    public function rules()
    {
        return [
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