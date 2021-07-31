<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteQuestionRequest;
use App\Models\Question;

class VoteQuestionController extends Controller
{
    public function __invoke(VoteQuestionRequest $request, Question $question)
    {
        $this->authorize('vote', $question);

        $question->votes()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['value' => $request->value]
        );

        return response()->json(['message' => 'Voting completed successfully']);
    }
}
