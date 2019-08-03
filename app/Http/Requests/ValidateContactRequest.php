<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateContactRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:255',
            'phone' => 'digits_between:8,10',
            'area' => 'required|string|min:3|max:30',
            'city' => 'string|min:3|max:30',
            'subject' => 'required|string|min:3|max:90',
            'message' => 'required|string|min:6',
        ];
    }
}
