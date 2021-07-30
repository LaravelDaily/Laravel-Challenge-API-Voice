

public function voice(StoreQuestionRequest $request){
    $question=Question::find($request->post('question_id'));
    if (!$question->user_id==auth()->id()){
        if($question->voice()->user_id == auth()->id()){
             $question->voice()->value = $request->post('value');
             if($voice->isDirty()){
                $voice->save();
                return response()->json([
                'message'=>'Voting update successfully'
                ]200);
             }
        }
        $question->voice()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->post('value')
        ]);

        return response()->json([
        'message'=>'Voting completed successfully'
        ]200);
    }
    throw new HttpResponseException(return response()->json([
            'message' => 'The user is not allowed to vote to your question'
        ],406));

    
}
