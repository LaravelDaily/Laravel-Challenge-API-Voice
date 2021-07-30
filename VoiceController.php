public function voice(Request $request){
    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    $question = Question::find($request->question_id);

    // We dont have to check if Question not exists. Because validation checks for us
    if ($question->user_id == auth()->id())
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ], 500);

    // check if user voted
    // We can use relation for query
    $vote = $question->votes()->where('user_id', auth()->id())->first();

    // We dont have to check twice for "if voice exists"
    if(isset(vote)){
        // if it's not equals to value there is one option left. It's not equal to value input. So we can use else.
        if ($vote->value === $request->value)
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote more than once'
            ], 500);
        else{
            $vote->update([
               'value'=>$request->value
            ]);
            return response()->json([
                'status' => 201,
                'message' => 'Your voice updated'
            ], 201);
        }
    }

    $question->votes()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->value
    ]);
    return response()->json([
        'status' => 200,
        'message' => 'Voting completed successfully'
    ], 200);
}
