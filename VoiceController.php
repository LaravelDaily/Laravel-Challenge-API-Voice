    public function voice(Request $request){
        $this->validate($request);
    
        $question=Question::findOrFail($request->post('question_id'));
        // can use laravel Gate
        if(!$this->isAllowedToVote($question)){
            return $this->errorResponse("The user is not allowed to vote to your question", 403);
        }
    
        //check if user voted 
        $voice=Voice::where([
            ['user_id','=',auth()->id()],
            ['question_id','=',$request->post('question_id')]
        ])->first();
        if ($this->isAlreadyVoted($voice, $request)) {
            return $this->errorResponse('The user is not allowed to vote more than once', 400);
        }
        if (!$this->isAlreadyVoted($voice, $request)){
            $voice->update([
                'value'=>$request->post('value')
            ]);
            return $this->successResponse('update your voice', 201);
        }
    
        $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);
        return $this->createdResponse('Voting completed successfully');
    }
    private function validate(Request $request){
        $request->validate([
            'question_id'=>'required|int|exists:questions,id',
            'value'=>'required|boolean',
        ]);
    }
    private function errorResponse($message, $code = 500){
        return response()->json([
            'status'=>$code,
            'message'=>$message
        ]);
    }
    private function successResponse($message){
        return response()->json([
            'status'=>200,
            'message'=>$message
        ]);
    }
    private function createdResponse($message){
        return response()->json([
            'status'=>201,
            'message'=>$message
        ]);
    }
    private function isAllowedToVote(Question $question){
        return $question->user_id==auth()->id();
    }
    public function isAlreadyVoted(Voice $voice, Request $request)
    {
        return $voice && $voice->value === $request->get('value');
    }
