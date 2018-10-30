<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployessRequest extends FormRequest
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
            'SSS' => 'regex:/\d\d-\d{7}-\d$/',
            'PHIC'=> 'regex:/\d\d-\d{9}-\d$/'
        ];
    }
}
