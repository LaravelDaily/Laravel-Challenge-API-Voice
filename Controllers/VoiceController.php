<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoiceRequest;

class VoiceController
{
    public function __construct()
    {
        /**
         * Either this way or you could assign the "auth" middleware to your route
         * 
         * @see https://laravel.com/docs/8.x/authentication#protecting-routes
         */
        $this->middleware('auth');
    }

    public function voice(VoiceRequest $request): JsonResponse // Consider renaming the method name to sth more meaningful
    {
        $request->validated();

        $questionId = $request->post('question_id');
        $voicePostValue = $request->post('value');
        $currentUserId = Auth::user()->id();

        $question = Question::findOrFail($questionId);
        
        if ($question->user_id === $currentUserId) {
            return response()->json([
                // 'status' => 500, >>> Ok if you want to but better mark the response with that status directly
                'message' => 'The user is not allowed to vote to your question'
            ], 500);
        }

        $voice = Voice::where([
            ['user_id' => $currentUserId],
            ['question_id' => $questionId],
        ])->first();

        if ($result = $this->handleVoiceIfPresent($voice, $voicePostValue)) {
            return $result;
        }

        $question->voice()->create([
            'user_id' => $currentUserId,
            'value' => $voicePostValue
        ]);

        return response()->json([
            // 'status' => 200, >>> Unnecessary because default response status is 200
            'message' => 'Voting completed successfully!'
        ]/*, 201 */); // You could argue though that it should be a 201
    }

    /**
     * @internal
     */
    protected function handleVoiceIfPresent(?Voice $voice, $voicePostValue): ?JsonResponse
    {
        if (!$voice) {
            return null;
        }

        if ($voice->value === $voicePostValue) {
            return response()->json([
                // 'status' => 500, >>> Ok if you want to but better mark the response with that status directly
                'message' => 'The user is not allowed to vote more than once!'
            ], 500);
        }
        
        /**
         * If you want to to it this way make sure to put the "value" field as $fillable inside your model!
         * Alternative:
         *      $voice->value = $voicePostValue;
         *      $voice->save();
         */
        $voice->update([
            'value' => $voicePostValue
        ]);

        return response()->json([
            // 'status' => 201, >>> Ok if you want to but better mark the response with that status directly (204 would also be more meaningful)
            'message' => 'Vote updated successfully!'
        ], 204);
    }
}