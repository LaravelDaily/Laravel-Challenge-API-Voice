<?php
namespace App\Http\Controllers;

// we can change Gate and VoiceRequest to real path. but for now i will leave it like this
use Gate;
use VoiceRequest;

use App\Models\Question;

class VoiceController extends Controller 
{
    public function voice(VoiceRequest $request) 
    {
        $question = Question::with('voices')->find($request->post('question_id'));

        if (!$question) {
            return $this->sendErrorResponse("Question not found.", 404);
        }

        if (!Gate::inspect('update', $question)->allowed()) {
            return $this->sendErrorResponse("You cant vote to your own question", 403);
        }

        $voice = $question->voices()->where('user_id', auth()->id())->first();

        if ($voice) {
            if ($voice->value === $request->post('value')) {
                return $this->sendErrorResponse("You cant vote more than once", 500);
            }

            $voice->update($request->only('value'));
            return $this->sendJsonResponse(['message' => 'Your vote has been updated.'], 201);
        }

        $question->voices()->create($request->only('user_id', 'value'));
        return $this->sendJsonResponse(['message' => 'Your vote has been submited.']);
    }
}
