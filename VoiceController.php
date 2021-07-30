<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VocieController extends Controller
{

    protected $rules = [
        'question_id' => 'required|int|exists:questions,id',
        'value' => 'required|boolean',
    ];

    public function voice(Request $request)
    {
        // if not validate return
        $request->validate($this->rules);

        // 404 if Question not find with where , so The user is not allowed to vote to your question
        $question = Question::where('user_id', auth()->id())->firstOrFail($request->question_id);

        $responseData = [
            'status' => 200,
            'message' => 'Voting completed successfully',
        ];

        $matchThese = [
            'value' => $request->value,
            'user_id' => auth()->id(),
        ];
        $voice = $question->voice()->firstOrCreate($matchThese, ['value' => $request->value]);

        // this if condition response is meaningless
        if ($voice->value === $request->value) {
            $responseData['status'] = 500;
            $responseData['message'] = 'The user is not allowed to vote more than once';
        } else {
            $question->voice()->updateOrCreate($matchThese, ['value' => $request->value]);
        }

        return response()->json($responseData, $responseData['status']);
    }

}