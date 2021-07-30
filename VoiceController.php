<?php
namespace App\Http\Controllers;

// we can change Gate and VoiceRequest to real path. but for now i will leave it like this
use Gate;
use VoiceRequest;

use App\Models\Question;

class TestController extends Controller 
{
    public function voice(VoiceRequest $request) 
    {
        $question = Question::with('voices')->find($request->post('question_id'));

        // return not found even if the question is belongs the user id
        if (!$question) {
            return $this->sendErrorResponse("Question not found.", 404);
        }

        // using gate to prevent update with rules
        if (!Gate::inspect('update', $question)->allowed()) {
            return $this->sendErrorResponse("You cant vote to your own question", 403);
        }

        // check if user voted 
        $voice = $question->voices()->where('user_id', auth()->id())->first();

        if (!$voice) {
            if ($voice->value === $request->post('value')) {
                return $this->sendErrorResponse("You cant vote more than once", 500);
            }

            $voice->update([
                'value' => $request->post('value')
            ]);

            return $this->sendJsonResponse(['message' => 'Your vote has been updated.'], 201);
        }

        // not sure if you want to change relation name. 
        // but i do want to change it to voices since it must be one to many relationship.
        $question->voices()->create([
            'user_id' => auth()->id(),
            'value' => $request->post('value')
        ]);

        return $this->sendJsonResponse(['message' => 'Your vote has been submitted.'], 200);
    }
}
