<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvents extends FormRequest
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
            "name" => "required|string|min:3",
            "eventType" => "required|string|min:3",
            "date" => "required",//|numric|exact:10
            "email" => "required",
            "phone" => "required",
            "location" => "required|string|min:3",
            "address" => "required|string|min:6",
            "description" => "required|string|min:12"
        ];
    }
}
