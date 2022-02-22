<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class ChangePasswordRequest extends Request
{
    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => trans('validation.required'),
            'current_password.string' => trans('validation.required'),
            'new_password.required' => trans('validation.required'),
            'new_password.string' => trans('validation.required'),
        ];
    }

    public function moreValidation(Validator $validator)
    {
        $validator->after(function ($validator) {

            if (\Auth::user()['password'] !== $this->input('current_password')) {

                $validator->errors()->add(
                    'current_password',
                    'Current password is invalid'
                );
            }
        });
    }
}