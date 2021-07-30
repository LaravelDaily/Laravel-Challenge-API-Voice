<?php 
    public function voice(Request $request){
        $request->validate([
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean',
        ]);

        $question = Question::findOrFail($request->post('question_id'));
        
        if ($question->user_id == auth()->id()){
            return response()->json($this->responseJsonDefined(500, 'You are not allowed to vote to your question'));
        }
            
        //check if user voted 
        $voice = Voice::where([
                    ['user_id', auth()->id()],
                    ['question_id' ,$request->post('question_id')]
                ])->first();

        if ($voice) {
            if ($voice->value == $request->post('value')) {
                return response()->json([
                    'status' => 500,
                    'message' => 'You are not allowed to vote more than once'
                ]);
            }

            $voice->value = $request->post('value');
            $voice->save();
            return response()->json($this->responseJsonDefined(201, 'Update your voice'));
        }

        $question->voice()->create([
            'user_id' => auth()->id(),
            'value' => $request->post('value')
        ]);
        return response()->json($this->responseJsonDefined(200, 'Voting completed successfully'));
    }

    private function responseJsonDefined($status, $message){
        return ['status' => $status, 'message' => $message];
    }