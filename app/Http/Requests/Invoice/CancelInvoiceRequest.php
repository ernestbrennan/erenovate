<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class CancelInvoiceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'invoice_id' => 'required|numeric|exists:guarantee_invoice,id',
        ];
    }

    public function messages()
    {
        return [];
    }
}