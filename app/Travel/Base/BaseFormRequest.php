<?php

namespace App\Travel\Base;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function all($keys = null)
    {
        return array_replace_recursive(
            parent::all(),
            $this->route()->parameters()
        );
    }

    public function validator()
    {
        $v = FacadeValidator::make($this->all(), $this->rules(), $this->messages(), $this->attributes());

        if (!$v->fails() && method_exists($this, 'moreValidation')) {
            $this->moreValidation($v);
        }

        return $v;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->getMessageBag()->toArray()
        ], 422));
    }
}