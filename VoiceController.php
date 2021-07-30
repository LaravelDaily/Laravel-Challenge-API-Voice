<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoiceRequest;
use App\Http\Status;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class VoiceController extends Controller
{
    public function voice(StoreVoiceRequest $request)
    {
        $question = Question::find($request->post('question_id'));

        if ($question->user_id == auth()->id()) {
            return response()->json([
                'status' => Status::ERROR,
                'message' => 'The user is not allowed to vote to your question'
            ]);
        }

        $voice = $question
            ->voice()
            ->where('question_id', '=', $request->post('question_id'))
            ->first();

        if (is_null($voice)) {
            $question->voice()->create([
                'user_id' => auth()->id(),
                'value' => $request->post('value')
            ]);

            return response()->json([
                'status' => Status::OK,
                'message' => 'Voting completed successfully'
            ]);
        }

        if ($voice->value === $request->post('value')) {
            return response()->json([
                'status' => Status::ERROR,
                'message' => 'The user is not allowed to vote more than once'
            ]);
        }

        $voice->update([
            'value' => $request->post('value')
        ]);

        return response()->json([
            'status' => Status::UPDATED,
            'message' => 'update your voice'
        ]);
    }
}
