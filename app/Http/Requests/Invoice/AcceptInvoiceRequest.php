<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class AcceptInvoiceRequest extends FormRequest
{
    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [];
    }
}