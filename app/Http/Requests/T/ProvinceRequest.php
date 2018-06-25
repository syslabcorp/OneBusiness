<?php

namespace App\Http\Requests\T;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceRequest extends FormRequest
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
            'Province' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'Province.required' => 'This field is required'
        ];
    }
}
