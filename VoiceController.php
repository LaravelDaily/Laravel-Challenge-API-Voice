<?php

    private $user;

    public function voice(Request $request)
    {
        $this->usre = Auth::user();
        $request->validate([
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean',
        ]);

        $question = Question::findOrfail($request->question_id);
        if ($question->user_id == $this->user->id)
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote to your question'
            ]);

        //check if user voted
        $voice = Voice::where('user_id', $this->user->id)->where('question_id',$request->question_id)->first();

        if (($voice) && $voice->value === $request->value) {
            return response()->json([
                'status' => 500,
                'message' => 'The user is not allowed to vote more than once'
            ]);
        } else if (($voice) && $voice->value !== $request->value) {
            //update or create first param is the condition of match values, second indicate fields to be updated
            $voice->updateOrCreate([
                'value' => $request->value
            ],
            [
                'user_id' => $this->user->id,
                'value' => $request->value
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'update your voice'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully'
        ]);

}
