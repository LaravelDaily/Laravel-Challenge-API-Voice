public function voice(Request $request){

    // can't vote on own question
    $question = Question::where('user_id','!=',Auth::id())->findOrFail($request->question_id));

    // assumes there is a relationship of a user's voices
    Auth::user()->voices()->createOrUpdate([
        'question_id' => $question->id,
        'value' => !! $request->value  // no need to validate just coerce to boolean, ideally change name
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting recorded successfully'
    ]);
}
