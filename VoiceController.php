public function voice(Request $request){

    // THIS VALIDATION SHOULD BE IN REQUEST CLASS //
    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    $question = Question::find($request->question_id);
    if (!$question){
        return $this->response(404, 'not found question');
    }

    if ($question->user_id == auth()->id()){
        return $this->response(500, 'The user is not allowed to vote to your question');
    }

    //check if user voted 
    $voice = Voice::where(['user_id' => auth()->id(), 'question_id' => $request->question_id])->first();
    if(!$voice){

        if($voice->value===$request->value){
            return $this->response(500, 'The user is not allowed to vote more than once');
        }

        $voice->update([
            'value'=>$request->post('value')
        ]);
        return $this->response(201, 'update your voice');

    }
    
    $question->voice()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->post('value')
    ]);

    return $this->response(200, 'Voting completed successfully');
}

public function response($status = 200, $message = '')
{
    return response()->json([
            'status' => $status,
            'message' => $message
    ]);
}