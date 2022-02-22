<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'project_id' => 'required|exists:tbl_project,id',
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => trans('validation.required'),
        ];
    }
}