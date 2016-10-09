<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
        switch (strtolower($this->method())) {
            case 'post':
                return [
                    'description' => ['required', 'string'],
                    'lecture_id' => ['required', 'integer']
                ];
            case 'put':
                return [
                    'answer' => ['required', 'string']
                ];
        }
    }
}
