<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @see https://laravel.com/docs/8.x/validation#form-request-validation
 */
class VoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean', // Consider renaming this to sth more meaningful
        ];
    }
}