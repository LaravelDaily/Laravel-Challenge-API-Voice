public function voice(Request $request)
{
    $request->validate([
        'question_id' => ['required', 'int', 'exists:questions,id'],
        'value' => ['required', 'boolean'],
    ]);

    $authUserId = auth()->id();
    $questionId = $request->post('question_id');
    $voteValue = $request->post('value');

    $question = Question::find($questionId);

    if (is_null($question)) {
        return response()->json([
            'status' => 404,
            'message' => 'Question not found',
        ]);
    }

    if ($question->user_id === $authUserId) {
        return response()->json([
            'status' => 401,
            'message' => 'The user is not allowed to vote to your question',
        ]);
    }

    $voice = $question->voice()
        ->where('user_id', $authUserId)
        ->first();

    if (is_null($voice)) {
        $question->voice()->create([
            'user_id' => $authUserId,
            'value' => $voteValue,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully',
        ]);
    }

    // Since I don't know the type of value property from the voice model,
    // Let's use unstrict comparison here
    if ($voice->value == $voteValue) {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote more than once',
        ]);
    }

    $voice->update([
        'value' => $voteValue,
    ]);

    return response()->json([
        'status' => 201,
        'message' => 'Update your voice',
    ]);
}
