 <? 
 public function question($request){

   $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    $question=$this->findQuestion($request);

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
 }

 public function findQuestion($request){

    return Question::find($request->post('question_id'));
 }