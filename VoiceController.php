public function voice(Request $request){

    $question = Auth::user()->questions()->findOrFail($request->question_id));

    Auth::user()->voices()->createOrUpdate([
        'question_id' => $question->id,
        'value' => !! $request->value
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting recorded successfully'
    ]);
}
