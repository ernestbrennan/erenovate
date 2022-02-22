<?php

namespace App\Http\Requests\Summary;

use Illuminate\Foundation\Http\FormRequest;

class GetByProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|',
            'per_page' => 'numeric'
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
