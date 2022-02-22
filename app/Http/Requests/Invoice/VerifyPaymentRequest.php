<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'invoice_id' => 'required|numeric|exists:guarantee_invoice,id',
            'description' => 'required|string',
            'files' => 'array',
        ];
    }

    public function messages()
    {
        return [];
    }
}