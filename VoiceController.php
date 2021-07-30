public function voice(VoiceAddRequest $request){
    $question=Question::find($request->post('question_id'));
    if (!$question)
        return $this->responseWithMessage('not found question ..', 404);

    if ($question->user_id==auth()->id())
        return $this->responseWithMessage('The user is not allowed to vote to your question', 500);

    $voice = Voice::ofUser(auth()->id())
                    ->ofQuestion($request->post('question_id'))
                    ->first();

    if(is_null($voice)) {
        $question->voice()->createForUser(auth()->id(), $request->post('value'));

        return $this->responseWithMessage('Voting completed successfully');
    }

    if($voice->hasValueOf($request->post('value'))) {
        return $this->responseWithMessage('The user is not allowed to vote more than once', 500);
    } else {
        $voice->updateValue($request->post('value'));
        
        return $this->responseWithMessage('update your voice', 201);
    }
}

private function responseWithMessage(string $message, int $status = 200)
{
    return response()->json([
        'status' => $status,
        'message' => $message,
    ]);
}