<?php

namespace App\Travel\Projects\Requests;

use App\Travel\Base\BaseFormRequest;

class WithdrawProjectRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|exists:guarantee_project,id',
        ];
    }
}