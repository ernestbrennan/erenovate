<?php
namespace App\Travel\Contracts\Requests;

use App\Travel\Base\BaseFormRequest;

class GetListRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
        ];
    }
}