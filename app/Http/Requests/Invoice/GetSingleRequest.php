<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class GetSingleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:in,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('validation.required'),
            'id.numeric' => trans('validation.numeric'),
            'id.exists' => trans('validation.exists'),
        ];
    }
}