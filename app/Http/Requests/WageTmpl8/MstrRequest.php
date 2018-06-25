<?php

namespace App\Http\Requests\WageTmpl8;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MstrRequest extends FormRequest
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
            'code' => 'required|max:50',
            'base_rate' => 'required|numeric',
            'position' => 'required',
            'dept_id' => 'required'
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'This field is required',
            'code.max' => 'Pay code should not exceed 50 characters',
            'base_rate.required' => 'This field is required',
            'base_rate.numeric' => 'This field must be a number',
            'position.required' => 'This field is required',
            'dept_id.required' => 'This field is required',
        ];
    }
}
