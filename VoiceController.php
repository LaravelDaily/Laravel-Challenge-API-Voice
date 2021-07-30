public function voice(Request $request){
    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    $question = Question::find($request->post('question_id'))->first();
    if (!$question)
    {
        return response()->json([
            'status'=>404,
            'message'=>'not found question ..'
        ],404);
    }

    if ($question->user_id == auth()->user()->id()){
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ],500);
    }

    //check if user voted 
    $voice = Voice::where([
        ['user_id','=',auth()->id()],
        ['question_id','=',$request->post('question_id')]
    ])->first();

    if ($voice && $voice->value === $request->post('value')) {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote more than once'
        ], 500);
    }
    if ($voice){
        $voice->update([
            'value'=>$request->post('value')
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'update your voice'
        ], 201);
    }

    $question->voice()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->post('value')
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting completed successfully'
    ]);
}