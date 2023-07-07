<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "firstname"=>['required','string','max:20'],
            "lastname" =>['required','string','max:20'], 
            "email"    =>['required','email'],   
            "phone"    =>['required','string','min:8','max:20'],
            "order_id"    =>['required'],
            "payment_option"=>['required','max:20'],
            "continue_success_url"=>['required','string'],  
        ];
    }
}
