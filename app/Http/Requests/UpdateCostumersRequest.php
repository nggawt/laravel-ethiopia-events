<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class UpdateCustomersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check() ){
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            "company" => "required|string|min:3",
            "businessType" => "required|string|min:3",
            "title" => "required|string|min:4",
            "contact" => "required|string|min:3",
            "email" => "required|string|email|unique:users,email|unique:customers,email",
            "tel" => "required|string|numeric|min:8",
            "address" => "required|string|min:4",
            "discription" => "required|string|min:12",
            "deals" => "required|string|min:6"
        ];
        
        if (request()->isMethod('patch') || request()->isMethod('put')) {

            return [];
        }else{
            return $data;
        }
    }
}
