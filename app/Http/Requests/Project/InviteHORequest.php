<?php

namespace App\Http\Requests\Project;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\Request;
use App\Models\User;

class InviteHORequest extends Request
{
    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => trans('validation.required'),
        ];
    }

    public function moreValidation(Validator $validator)
    {
        $validator->after(function ($validator) {

            $email = $this->input('contract')['invite_info']['email'];

            $user = User::where('email', $email)->first();

            if ($user && $user->role === User::ROLE_CONTRACTOR) {

                $validator->errors()->add(
                    'role_contractor',
                    ''
                );
            }
            
        });
    }
}