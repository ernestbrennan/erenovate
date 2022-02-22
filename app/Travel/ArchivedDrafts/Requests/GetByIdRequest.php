<?php

namespace App\Travel\ArchivedDrafts\Requests;

use App\Travel\Base\BaseFormRequest;
use Illuminate\Validation\Validator;
use Auth;

class GetByIdRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:guarantee_archived_drafts,id'
        ];
    }

    public function moreValidation(Validator $validator)
    {
        $validator->after(function ($validator) {

            $user = Auth::user();

            if (!$user->archived_drafts()->find($this->id)) {

                $validator->errors()->add('id', 'Owner exception');
            }
        });
    }
}