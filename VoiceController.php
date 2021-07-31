public function voice(VoiceRequest $request, Question $question){
    $validated = $request->validated();

    $question->voice()->updateOrCreate([
        ['user_id' => auth()->user()->id]
        'value' => $validated['value']
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting completed successfully'
    ]);
}