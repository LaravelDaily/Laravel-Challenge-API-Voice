<?php

// Controller
class VoiceController
{
    public function voice(VoiceRequest $request)
    {
        $question = Question::findOrFail($request->question_id);

        $question->voice()->updateOrCreate([
            'user_id' => auth()->id(),
            'value' => $request->value,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully',
        ]);
    }

}

// Form Request
use Illuminate\Foundation\Http\FormRequest;

class VoiceRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user_id != auth()->id();
    }

    public function rules()
    {
        return [
            'question_id' => 'required|integer|exists:questions,id',
            'value' => 'required|boolean',
        ];
    }
}

