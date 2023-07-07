<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'        => [$this->isPostMethod(),'string','max:50'],
            'description' => ['string'], 
            'price'       => [$this->isPostMethod(),'numeric'], 
            'category_id' => [$this->isPostMethod(),'numeric'], 
            'image'       => ['image','mimes:jpeg,jpg,png,gif','max:10000']
        ];
    }


    // Post method or not (post is create // not is update)
    private function isPostMethod(){
        if($this->isMethod('post')){
            return 'required';
        }

        return '';
    }
}
