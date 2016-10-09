<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    use ThrowsApiErrors;

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
        switch (strtolower($this->method())) {
            case 'post':
                return [
                    'email' => ['required', 'email', 'regex:/.+westerdals\.no/', 'unique:users'],
                    'name' => ['required', 'string'],
                    'password' => ['required', 'string'],
                ];

            case 'put':
                return [
                    'email' => ['email', 'regex:/.+westerdals\.no/', 'unique:users'],
                    'name' => ['string'],
                    'password' => ['string'],
                ];
        }
        
    }
}
