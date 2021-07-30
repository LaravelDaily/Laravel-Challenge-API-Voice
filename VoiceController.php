<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;

class VoiceController extends Controller
{
    public function voice(QuestionRequest $request)
    {
        $question = Question::findOrFail($request->post('question_id'));

        if ($question->user_id == auth()->id()) {
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote to your question'
            ], 500);
        }

        $voice = Voice::firstOrCreate([
            ['user_id' => auth()->id(), 'question_id' => $request->post('question_id')],
            ['value' => $request->post('value')]
        ]);

        if (!$voice->wasRecentlyCreated) {
            if ($voice->value !== $request->post('value')) {
                $voice->update([
                    'value' => $request->post('value')
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Update your voice'
                ]);
            }
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote more than once'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully'
        ]);
    }
}
