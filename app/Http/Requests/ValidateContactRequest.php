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
            'user_id' => 'number',
            'email_from' => 'required|string|email|max:255',
            'email_to' => 'required|string|email|max:255',
            'name' => 'required|string|min:3|max:30',
            'subject' => 'required|string|min:3|max:90',
            'body' => 'required|string|min:6|max:255',
            'area' => 'string|min:3|max:30',
            'city' => 'string|min:3|max:30',
            'phone' => 'digits_between:8,10',
            'date' => 'date',
        ];
    }
}
