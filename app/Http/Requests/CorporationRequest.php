<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorporationRequest extends FormRequest
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
            'corp_name' => 'required',
            'database_name' => 'required',
            'corp_type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'corp_name.required' => 'This field is required',
            'database_name.required' => 'This field is required',
            'corp_type.required' => 'This field is required'
        ];
    }
}
