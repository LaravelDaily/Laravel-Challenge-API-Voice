<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use  App\Models\Question;
use  App\Models\Voice;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoiceRequest;
use Exception;
class VoiceController extends Controller
{
    public function voice(VoiceRequest $request)
    {
        try {
            $authId = auth()->id();
            $question_id = $request->post('question_id');
            $value = $request->post('value');
    
            $question = Question::select('user_id')->where('id', $question_id)->first();
            if ($question->user_id == $authId) {
                return $this->jsonResponse(500, 'The user is not allowed to vote to your question');
            }
    
            // Check if user voted 
            $voice = Voice::select('value')->where([
                ['user_id', '=', $authId],
                ['question_id', '=', $question_id]
            ])->first();
    
            if($voice) {
                if ($voice->value === $value) {
                    return $this->jsonResponse(403, 'The user is not allowed to vote more than once');
                } else if ($voice->value !== $value) {
                    $voice->update([
                        'value' => $value
                    ]);
                    return $this->jsonResponse(201, 'update your voice');
                } 
            } else {
                $question->voice()->create([
                    'user_id' => $authId,
                    'value' => $value
                ]);
            }
        } catch (Exception $e) {
            // var_dump($e->getMessage());
            return $this->jsonResponse(500, 'Oops... Something Wrong');
        }
        
        return $this->jsonResponse(200, 'Voting completed successfully');
    }

    private function jsonResponse($status, $message) {
        return response()->json([
            'status' => intval($status),
            'message' => strval($message)
        ], 200);
    }
}
