<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Travel\Users\User;
use Illuminate\Validation\Validator;

class SignInUserRequest extends Request
{
    public function rules()
    {
        return [
            'email' => 'exists:user,email',
        ];
    }

    public function moreValidation(Validator $validator)
    {
        $validator->after(function ($validator) {

            $user = User::where('email', $this->input('email'))->first();

            if ($user->password !== $this->input('password')) {

                $validator->errors()->add('password', '');
            }

        });
    }
}