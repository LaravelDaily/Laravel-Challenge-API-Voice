<?php

class CreateVoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_id' => 'required|int|exists:questions,id',
            'value'       => 'required|boolean',
        ];
    }
}