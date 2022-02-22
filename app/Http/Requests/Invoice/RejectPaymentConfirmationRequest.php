<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class RejectPaymentConfirmationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'invoice_id' => 'required|numeric|exists:guarantee_invoice,id',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'invoice_id.required' => trans('validation.required'),
            'invoice_id.numeric' => trans('validation.numeric'),
            'invoice_id.exists' => trans('validation.exists'),
            'description.required' => trans('validation.required'),
        ];
    }
}