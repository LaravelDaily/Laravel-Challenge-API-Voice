<?

public function voice(VoiceRequest $request){
    $question = Question::findOrFail($request->post('question_id'));
    if ($question->user_id == auth()->id())
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ]);

    $voice = Voice::firstOrCreate (
        ['user_id' => auth()->id(), 'question_id' => $request->post('question_id')],
        ['value' => $request->post('value')]
    );

    if(!$voice->wasRecentlyCreated){
        if ( $voice->value !== $request->post('value')) {
            $voice->update([
                'value' => $request->post('value')
            ]);
            return response()->json([
                'status' => 201,
                'message' => 'update your voice'
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

