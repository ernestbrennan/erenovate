<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class GetNoteRequest extends FormRequest
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
            'chat_hash.required' => trans('validation.required'),
            'chat_hash.numeric' => trans('validation.required'),
            'chat_hash.exists' => trans('validation.exists'),
        ];
    }
}