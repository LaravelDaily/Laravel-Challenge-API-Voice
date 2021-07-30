public function voice(Request $request) {
    $request->validate([
        'question_id' => 'required|int|exists:questions',
        'value' => 'required|boolean',
    ]);

    try {
        $question = Question::findOrFail($request->post('question_id'));
    }
    catch(\Exception $e) {
        return response()->json([
            'message' => 'Question not found!'
        ], 404);
    }

    if ($question->user_id == auth()->id())
        return response()->json([
            'message' => 'The user is not allowed to vote to your question!'
        ], 500);

    //check if user voted 
    $voice = Voice::where([
        ['user_id','=', auth()->id()],
        ['question_id', '=', $request->post('question_id')]
    ])->first();

    if (!is_null($voice) && $voice->value == $request->post('value')) {
        return response()->json([
            'message' => 'The user is not allowed to vote more than once'
        ], 500);
    }
    else if (!is_null($voice) && $voice->value != $request->post('value')) {
        $voice->update([
            'value'=>$request->post('value')
        ]);

        return response()->json([
            'message' => 'Voice updated'
        ], 201);
    }

    $question->voice()->create([
        'user_id' => auth()->id(),
        'value' => $request->post('value')
    ]);

    return response()->json([
        'message' => 'Voting completed successfully'
    ]);
}
