<?php
public function voice(Request $request){

    $validated = $request->validate([
        'question_id' => 'required | int | exists:questions,id',
        'value' => 'required | boolean'
    ]);

    $question = Question::find($validated->question_id);

    if (!$question){
     return response()->json(['message' => 'Not found question ..'],404);
    }

    if($question->user_id == auth()->id) {
        return response()->json(['message' => 'The user is not allowed to vote to your question'], 500);
    }

    //check if user voted
    $voice = Voice::where(['user_id', auth()->id()], ['question_id', $validated->question_id])->first();

    if ($voice && $voice->value === $validated->value) {
        return response()->json(['message' => 'The user is not allowed to vote more than once'],500);
    }

    else if($voice && $voice->value !== $validated->value) {
        $voice->update(['value' => $validated->value]);
        return response()->json(['message' => 'update your voice'], 201);
    }

    $question->voice()->create([
        'user_id' => auth()->id(),
        'value' => $validated->value
    ]);
    return response()->json(['message' => 'Voting completed successfully']);
}
