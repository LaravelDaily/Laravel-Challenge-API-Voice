public function voice(Request $request)
{
        
        /* Validate incoming request */
        
        $validation = $this->validate_request($request->all(), [
            'question_id'=>'required|int|exists:questions,id',
            'value'=>'required|boolean',
        ]);
        
        if ($validation)
        {
            return response()->json([
                'status' => false,
                'message' => $validation
            ], 422);
        }
        
        /* Get Questions data by id */
        $question = Question::find($request->post('question_id'));
        
        if(empty($question))
        {
            return response()->json([
                'status' => false,
                'message' => 'No Record Found !!!'
            ], 404);
        }
        
        if ($question->user_id==auth()->id())
        {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to vote for your question'
            ], 200);
        }
        
        //check if user voted
        $voted=Voice::where([
            ['user_id','=',auth()->id()],
            ['question_id','=',$request->post('question_id')]
        ])->first();
        
        if(!empty($voted))
        {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to vote more that once'
            ], 200);
        }
                
        $rs = $question->voice()->create([
            'user_id'=>auth()->id(),
            'value'=>$request->post('value')
        ]);
                
        if($rs)
        {
            return response()->json([
                'status'=> true,
                'message'=>'Voting completed successfully'
            ], 200);
        }
                
        return response()->json([
            'status'=> false,
            'message'=>'Something went wrong !!!'
        ], 500);
    }
    
    
    /* Validate incoming request */
    private function validate_request($payload, $rules)
    {
        $validator = Validator::make($payload, $rules);
        
        if ($validator->fails()) {
            $messages = $validator->messages();
            return $messages->first();
        }
    }