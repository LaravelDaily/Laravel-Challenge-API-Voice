<?php

class VoiceController{
    private static function output($status, $message) {
        // return the response with status equal to $status and message equal to $message
        return response()
        ->json(compact('status', 'message'));
    }
    public function voice(Request $request){
        $data = $request->validate([
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean',
        ]);

        $question = Question::find($data['question_id']);
        if (!$question)
            return self::output(404, 'not found question. ');

        if ($question->user_id == auth()->id())
            return self::output(500, 'The user is not allowed to vote to your question');

        //check if user voted

        // the question_id of the voice has to be equal to our question's id and equality of user_id and auth()->id checked. so they have an one to one relationship
        $voice = $question->voice;
        if(is_null($voice)){
            $question->voice()->create([
                'user_id' => auth()->id(),
                'value' => $data['value']
            ]);

            return self::output(200, 'Voting completed successfully');
        }
        if ($voice->value === $data['value']) {
            return self::output(500, 'The user is not allowed to vote more than once');
        }else if ($voice->value !== $data['value']){
            $voice->update([
                'value' => $data['value']
            ]);
            return self::output(201, 'update your voice');
        }

    }
}
