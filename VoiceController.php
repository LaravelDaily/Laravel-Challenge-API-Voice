<?php
public function voice(VoiceRequest $request){
    
    // Moved requset to validation
    $request->validated();

    // FindOrFail is suitable here? Never used in api
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
    ]);

    if ($voice->exists()) {
        // If voice exists the just update the value.
        $getVoice = $voice->first();
        $getVoice->update([
            'value'=> $request->post('value')
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'update your voice'
        ]);

    }else{
        // Create new if not exists
        $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);
    
        return response()->json([
            'status'=>200,
            'message'=>'Voting completed successfully'
        ]);
        
    }
    
}