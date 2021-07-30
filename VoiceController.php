public function voice(Request $request){
    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    $question = Question::findOrFail($request->question_id);

    if ($question->user_id == auth()->id())
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ]);

    //check if user voted 
    $voice = Voice::where([
        ['user_id', auth()->id()],
        ['question_id', $request->question_id]
    ])->firstOrFail();

    if ($voice->value === $request->value) {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote more than once'
        ]);
    }else if ($voice->value !== $request->value){
        $voice->update([
            'value' => $request->value
        ]);

        return response()->json([
            'status'=>201,
            'message'=>'update your voice'
        ]);
    }

    $question->voice()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->value
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting completed successfully'
    ]);
}
