public function voice(Request $request)
{

    // can't voice on own question
    $question = Question::where('user_id','<>',Auth::id())->findOrFail($request->question_id));

    // assumes there is a relationship of a user's voices
    Auth::user()->voices()->updateOrCreate(
        ['question_id' => $question->id],
        ['value' => !! $request->value]     // no need to validate just coerce to boolean, ideally change name
    );

    return response()->json([
        'status' => 200,
        'message' => 'Voting recorded successfully'
    ]);
}

// version using route model binding 
public function voice(Request $request, Question $question)
{

    // can't voice on own question
    abort_if($question->user_id == Auth::id()),403,'You cannot voice your own question');

    // assumes there is a relationship of a user's voices
    Auth::user()->voices()->updateOrCreate(
        ['question_id' => $question->id],
        ['value' => !! $request->value]     // no need to validate just coerce to boolean, ideally change name
    );

    return response()->json([
        'status' => 200,
        'message' => 'Voting recorded successfully'
    ]);
}
