public function voice(VoiceRequest $request,Question $question){    

    return (new VoteService)->validateOrUpdateOrCreate($request->validated(),$question);

}