<?php

namespace App\Http\Requests;

use App\Models\Lot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LotUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'operation_type' => ['required', Rule::in(Lot::typesList())],
            'nomenclature' => 'required|max:3000',
            'NDS' => ['required', Rule::in([0, 10, 18])],
            'sum' => 'required|numeric',
            'fee' => 'required|numeric|between: 2,99',
        ];
    }
}
