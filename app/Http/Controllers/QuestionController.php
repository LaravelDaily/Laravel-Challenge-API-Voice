<?php

namespace App\Http\Controllers;

use App\Http\Request\VotingRequest;
use App\Models\Question;
use App\Models\Voice;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function voice(VotingRequest $request, Question $question)
    {
        $this->authorize('vote', $question);

        // If User has not voted on this question, a Voice will be created.
        // If User voted on this question previously, it will only update the Voice's value attribute, if it has changed.
        auth()->user()->voices()->updateOrCreate(
            ['question_id' => $question->id],
            ['value' => $request->value]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully.'
        ]);
    }
}
