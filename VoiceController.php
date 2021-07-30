<?php

namespace App\Http\Controllers;
class VoiceController extends Controller
{
    public function voice(VoiceRequest $request){
        $question_id = $request->post('question_id');
        $value = $request->post('value');
        $auth_id = auth()->id();

        $question = Question::find($question_id);

        if (!$question) return $this->response('not found question ..', 404);

        if ($question->user_id == $auth_id) return $this->response('The user is not allowed to vote to your question', 500);

        //check if user voted
        $voice = Voice::where([
            ['user_id', $auth_id],['question_id', $question_id]
        ])->first();

        if ($voice && $voice->value === $value) {
            return $this->response('The user is not allowed to vote more than once', 500);
        }else if ($voice && $voice->value !== $value){
            $voice->update([
                'value '=> $value
            ]);

            return $this->response('update your voice', 201);
        }

        $question->voice()->create([
            'user_id' => $auth_id,
            'value' => $value
        ]);

        return $this->response('Voting completed successfully');
    }

    public function response($message = 'Data retrieved successfully', $status = 200){
        return response()->json([
            'status' => 200,
            'message' => $message
        ]);
    }
