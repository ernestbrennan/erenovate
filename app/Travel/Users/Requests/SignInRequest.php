<?php

namespace App\Travel\Requests;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Users\User;
use Illuminate\Validation\Validator;

class SignInRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|exists:user,email',
            'password' => 'required|string',
        ];
    }

    public function moreValidation(Validator $validator)
    {
        $validator->after(function ($validator) {

            $user = User::where('email', $this->input('email'))->first();

            if ($user->password !== $this->input('password')) {

                $validator->errors()->add('password', 'Password not valid');
            }
        });
    }
    public function messages()
    {
        return [
            'email.exists' => 'That email address not exists'
        ];
    }
}
