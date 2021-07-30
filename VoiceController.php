public function voice(CreateVoiceRequest $request){
    $question = Question::findOrFail($request->question_id); //find the question or fail

    abort_if($question->user_id === auth()->id(), 403, 'The user is not allowed to vote to your question');

    //check if user voted 
    $voice = Voice::where([
        'user_id' => auth()->id(),
        'question_id' => $request->question_id
    ])->first();

    abort_if($voice && $voice->value === $request->value, 403, 'The user is not allowed to vote more than once'); //since the user has a vote already.

    if($voice){
        $voice->update([
            'value' => $request->value
        ]);

        return response()->json([
            'status'    =>  200,
            'message'   =>  'update your voice'
        ]);
    }

    $question->voice()->create([
        'user_id'   =>  auth()->id(),
        'value'     =>  $request->value
    ]);

    return response()->json([
        'status'=>201,
        'message'=>'Voting completed successfully'
    ]);
}