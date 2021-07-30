<?php

class VoiceController extends Controller
{
    public function voice(VoiceRequest $request)
    {
        $question = Question::with('voice')->find($request->question_id);
        if ($question->user_id == auth()->id()) {
            return response()->json(['message' => 'The user is not allowed to vote to your question'], 401);
        }

        $voice = $question->voice->where('user_id', auth()->id())->first();
        if ($voice) {
            if ($voice->value === $request->value) {
                return response()->json(['message' => 'The user is not allowed to vote more than once'], 401);
            }

            $voice->update([
                'value' => $request->value
            ]);
            return response()->json(['message' => 'update your voice'], 201);
        }

        $question->voice()->create([
            'user_id' => auth()->id(),
            'value' => $request->value
        ]);
        return response()->json(['message' => 'Voting completed successfully'], 200);
    }
}
