<!-- public function voice(Request $request){
    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    $question=Question::find($request->post('question_id'));
    if (!$question)
        return response()->json([
            'status'=>404,
            'message'=>'not found question ..'
        ]);
    if ($question->user_id==auth()->id())
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ]);

    //check if user voted 
    $voice=Voice::where([
        ['user_id','=',auth()->id()],
        ['question_id','=',$request->post('question_id')]
    ])->first();
    if (!is_null($voice)&&$voice->value===$request->post('value')) {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote more than once'
        ]);
    }else if (!is_null($voice)&&$voice->value!==$request->post('value')){
        $voice->update([
            'value'=>$request->post('value')
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'update your voice'
        ]);
    }

    $question->voice()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->post('value')
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting completed successfully'
    ]);
} -->

public function voice(Request $request) {

    // using validator() helper method will throw correct validation error

    validator ($request->all(),[
        'question_id' => 'required|int|exists:questions,id',
        'value' => 'required|boolean',
    ])->validate();


    // Assuming Voice model is associated with one Question model

    $voice = Voice::with('question')
        ->where('question_id', $request->post('question_id')
        ->where('user_id', auth()->id)
        ->first();

    if ($voice !== null && $voice->value !== $request->post('value')) {
        $voice->update([
            'value'=>$request->post('value')
        ]);

        return response()->json([
            'message'=>'Your voice has been updated'
        ], 201);
    }

    else if ($voice !== null && $voice->value == $request->post('value')) {
        return response()->json([
            'message' => 'Sorry you are not allowed to vote more than once'
        ], 500);
    }
    else {
        $voice->create([
            'user_id' => auth()->id(),
            'question_id' => $request->post('question_id),
            'value' => $request->post('value')
        ]);

        return response()->json([
            'message'=>'Voting completed successfully'
        ], 200);
    }

}

// The modal function that shows the one to one association of Voice to Question Model

public function question()
    {
        return $this->hasOne(Question::class);
    }
