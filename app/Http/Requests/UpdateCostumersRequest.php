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
            "company" => "required|min:3",
            "businessType" => "required|min:3",
            "title" => "required|min:3",
            "contact" => "required|min:3",
            "email" => "required|email",
            "tel" => "required|min:8",
            "address" => "required|min:3",
            "discription" => "required|min:6"
        ];
        
        if (request()->isMethod('patch') || request()->isMethod('put')) {

            return [];
        }else{
            return $data;
        }
    }
}
