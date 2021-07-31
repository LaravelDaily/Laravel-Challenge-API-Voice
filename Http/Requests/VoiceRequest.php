<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean',
        ];
    }
}
