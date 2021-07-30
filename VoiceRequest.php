<?php

namespace App\Http\Requests\Hub\Users;

use Illuminate\Foundation\Http\FormRequest;

class MyProfileUpdateEmail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // for this example
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_id' => ['required', 'numeric', 'exists:questions,id'],
            'value' => ['required', 'boolean']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        // for this example let's stick with default messages
    }
}
