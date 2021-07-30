public function voice(Request $request)
{
    $request->validate([
        'question_id' => [ 'required', 'integer', 'exists:questions,id' ],
        'value' => [ 'required', 'boolean' ],
    ]);

    $question = Question::findOrFail($request->question_id);

    if ($question->user_id == auth()->id()) {
        return response()->json([
            'status' => 500, // left this one here, because the original author might have a purpose for this
            'message' => __('The user is not allowed to vote to your question')
        ], 500);
    }

    //check if user voted
    $voice = $request->user()
        ->voices()  // In these scenario, I prefer to use hasMany voices relationship from the User model instead
        ->where('question_id', $request->question_id)
        ->first();

    if ($voice && $voice->value == $request->value) {
        return response()->json([
            'status' => 500, // left this one here, because the original author might have a purpose for this
            'message' => __('The user is not allowed to vote more than once')
        ], 500);
    }

    if ($voice && $voice->value != $request->value) {
        $voice->update([ 'value' => $request->value ]);

        return response()->json([
            'status' => 201,
            'message' => __('Your voice has been updated')
        ], 201);
    }

    $question->voice()->create([
        'user_id' => auth()->id(),
        'value' => $request->value
    ]);

    return response()->json([
        'status' => 200,
        'message' => __('Voting completed successfully')
    ]);
}
