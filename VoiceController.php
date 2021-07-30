public function voice(Request $request){

    $validated_data = $request->validated();

    // since it's an api I would stick with find() and return errors with the below json
    $question = Question::find($validated_data['question_id']);

    if (!$question)
    {
        return response()->json([
            'status' => 404,
            'message' => 'not found question ..'
        ]);
    }

    if ($question->user_id === auth()->user()->id())
    {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ]);
    }


    //check if user voted 
    $voice = Voice::query()
                    ->select('value')
                    ->where([
                        ['user_id', auth()->user()->id()],
                        ['question_id', $validated_data['question_id']]
                    ])->first();

    if (!$voice)
    {
        if ($voice->value === (bool)$validated_data('value'))
        {
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote more than once'
            ]);
        }
        if ($voice->value !== (bool)$validated_data['value'])
        {
            $voice->update([
                'value' => $validated_data['value']
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'update your voice'
            ]);
        }
    }

    $question->voice()->create([
        'user_id' => auth()->user()->id(),
        'value' => $validated_data['value']
    ]);

    return response()->json([
        'status' => 200,
        'message' => 'Voting completed successfully'
    ]);
}