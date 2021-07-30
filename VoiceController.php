public function voice(VoiceRequest $request){
    $question=Question::find($request->post('question_id'));
    if (!$question)
        return response()->json([
            'message'=>'not found question ..'
        ], 404);
    if ($question->user_id==auth()->id())
        return response()->json([
            'message'=>'The user is not allowed to vote to your question'
        ], 500);

    //check if user voted
    $voice=Voice::where([
        'user_id'=>auth()->id(),
        'question_id'=>$request->post('question_id')
    ])->first();

    if($voice){
        $voice->update([
            'value'=>$request->post('value')
        ]);
        
        return response()->json([
            'message'=>'updated successfully'
        ], 200);
    }else{
        $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);

        return response()->json([
            'message'=>'Voting completed successfully'
        ], 200);
    }
}