<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteQuestionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'value' => ['required', 'boolean']
        ];
    }
}
