<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VotingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'value' => [
                'required',
                'boolean',
            ],
        ];
    }
}