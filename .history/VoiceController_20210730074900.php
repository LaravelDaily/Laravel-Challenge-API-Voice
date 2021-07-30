<?php

function voice(Request $request)
{
    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);
    
    $AuthUser = auth()->user();

    $question = Question::findOrFail($request->question_id);
    
    if ($question->user_id == $AuthUser->id) {
        return response()->json([
            'status' => 406, //Not Acceptable
            'message' => 'The user is not allowed to vote to your question'
        ]);
    }
        
    //check if user voted
    $voice = $AuthUser->voices()->where('question_id', $request->question_id)->first();
    if ($voice && $voice->value == $request->value) {
        return response()->json([
            'status' => 406, //Not Acceptable
            'message' => 'The user is not allowed to vote more than once'
        ]);
    } elseif ($voice) {
        $voice->update([
            'value'=>$request->post('value')
        ]);
        return response()->json([
            'status'=> 202, // Accepted
            'message'=>'update your voice'
        ]);
    } else {
        $AuthUser->voices()->create([
            'question_id'=> $request->question_id,
            'value'=>$request->post('value')
        ]);
        return response()->json([
            'status'=>200,
            'message'=>'Voting completed successfully'
        ]);
    }
    
}

function voice2(Request $request)
{
    // this version more complex to debuging I think 

    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);
    
    $AuthUser = auth()->user();

    $question = Question::findOrFail($request->question_id);
    
    if ($question->user_id == $AuthUser->id) {
        return response()->json([
            'status' => 406, //Not Acceptable
            'message' => 'The user is not allowed to vote to your question'
        ]);
    }
        
    //check if user voted
    $voice = $AuthUser->voices()->where('question_id', $request->question_id)->first();
    if ($voice && $voice->value == $request->value) {
        $status = 406;
        $message = 'The user is not allowed to vote more than once';
        
    } elseif ($voice) {
        $voice->update([
            'value'=>$request->post('value')
        ]);
        $status = 202; // Accepted
        $message = 'Your Voting updated successfully';
    } else {
        $AuthUser->voices()->create([
            'question_id'=> $request->question_id,
            'value'=>$request->post('value')
        ]);
        $status = 201; // Created
        $message = 'Voting completed successfully';
    }
    return response()->json([
        'status' => $status,
        'message' => $message
    ]);
    
}