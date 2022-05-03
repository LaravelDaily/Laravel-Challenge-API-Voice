<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\UpsertVoiceRequest;
use App\Models\Question;
use App\Models\Voice;

class VoiceController extends Controller
{
    // FIRST SCENARIO - without changing the method's inputs and outputs
    public function voice(UpsertVoiceRequest $request)
    {
        $question = Question::find($request->post('question_id'));
        throw_if(empty($question), new NotFoundException());
        throw_if($question->user_id == auth()->id(), new NotAllowedException());

        $voice = Voice::updateOrCreate(
            ['user_id' => auth()->id(), 'question_id' =>$request->post('question_id')],
            ['value' => $request->post('value')]
        );

        return response()->json([
            'status' => $voice->wasRecentlyCreated ? 200 : 201,
            'message' => $voice->wasRecentlyCreated ? 'Voting completed successfully' : 'update your voice'
        ]);
    }
}
