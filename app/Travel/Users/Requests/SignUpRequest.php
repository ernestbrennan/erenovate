<?php

namespace App\Travel\Requests;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Users\User;
use Illuminate\Validation\Rule;

class SignUpRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:user,email',
            'first_name' => 'required|string|min:6',
            'password' => 'required|string|min:6',
            'role' => [
                'required',
                Rule::in([User::ROLE_HOMEOWNER, User::ROLE_CONTRACTOR])
            ],
        ];
    }
}