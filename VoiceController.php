<?php

/**
 * routes/api.php
 * Route::post('vote/{question}', [QuestionController::class, 'vote'])
 */


/**
 * VoteRequest class
 *  [
 *   'value'=>'required|boolean',
 * ]
 */


public function voice(VoteRequest $request, Question $question){

    if ($question->user_id==auth()->id()){
        return abort(403, 'You can not vote on your question');
    }

    $question->votes()->updateOrCreate(
        ['user_id' => auth()->id()],
        ['value' => $request->validated('value')]
    );
    
    return response()->json([
        'message'=>'Voting completed successfully'
    ]);
}
