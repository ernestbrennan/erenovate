<?php

namespace App\Travel\Summary\Requests;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Users\User;
use Illuminate\Validation\Validator;

class ByProjectRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|exists:guarantee_project,id',
        ];
    }

}
