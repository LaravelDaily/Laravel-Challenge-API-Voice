<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voice;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\VoiceRequest;

class VoiceController extends Controller
{
    public function voice(VoiceRequest $request)
    {
        $authId = auth()->id();
        $questionId = $request->post('question_id');
        $value = $request->post('value');

        $question = Question::findOrFail($questionId);

        // if the user is not allowed to vote more then once,
        //then the logic is that he can't either update or create another voting value
        Voice::where([
            ['user_id', $authId],
            ['question_id', $questionId]
        ])->firstOr(function () use ($authId, $question, $value) {
            //the relational function should be named voices because one question can have many voices.
            $question->voices()->attach($authId, ['value' => $value]);

            return response()->json([
                'status' => 'success',
                'message' => 'Voting completed successfully'
            ], Response::HTTP_OK);
        });

        // if the voice was found and not created it means that the user has been already voted.
        return response()->json([
            'status' => 'error',
            'message' => 'The user is not allowed to vote more than once'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
