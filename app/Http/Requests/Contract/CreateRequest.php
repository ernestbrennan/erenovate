<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'guarantee_project_id' => 'required|numeric|exists:guarantee_project,id'
        ];
    }

    public function messages()
    {
        return [
            'guarantee_project_id.required' => trans('validation.required'),
            'guarantee_project_id.numeric' => trans('validation.numeric'),
            'guarantee_project_id.exists' => trans('validation.exists'),
        ];
    }
}