<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VoiceRequest;
use App\Models\Question;
use App\Models\Voice;

class VoiceController extends Controller
{
    /**
     * voice()
     */
    public function voice(VoiceRequest $request)
    {

        $question = Question::findOrFail($request->input('question_id'));
        
        if($question->user_id == auth()->user()->id()){
            abort(500, 'The user is not allowed to vote to your question');
        }

        $voice = Voice::where([
            ['user_id', auth()->user()->id()],
            ['question_id', $request->input('question_id')]
        ])
        ->first();
        
        if(!is_null($voice)){

            if($voice->value === $request->input('value')){
                abort(500, 'The user is not allowed to vote more than once');
            } else {
                $voice->update([
                    'value'=>$request->input('value')
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Update your voice'
                ]);
            }

        }

        $question->voice()->create([
            'user_id' => auth()->user()->id(),
            'value' => $request->input('value')
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully'
        ]);

    }
}
