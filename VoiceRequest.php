<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user_id != auth()->id();
    }

    public function messages()
    {
        return [
            'question_id.exists' => 'Not found question',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_id' => 'required|integer|exists:questions,id',
            'value' => 'required|boolean',
        ];
    }
}
