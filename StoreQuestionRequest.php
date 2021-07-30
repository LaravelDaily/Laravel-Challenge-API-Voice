<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewsRequest extends FormRequest
{
    /**
     * Add parameters to be validated
     * 
     * @return array
     */

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
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean',
        ];
    }




    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            response()->json(['errors' => $errors,
                'message' => 'Denied', 
            ], 406)
        );
    }
}
