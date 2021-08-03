<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoiceRequest;
use App\Models\Question;
use Illuminate\Http\Response;

class VoiceController extends Controller
{
    public function voice(VoiceRequest $request, Question $question)
    {
        $voice = $question->voices()->updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'value' => $request->input('value'),
        ]);

        if ($voice->wasRecentlyCreated) {
            return response(['message' => 'Voting completed successfully'], Response::HTTP_CREATED);
        }

        if ($voice->wasChanged('value')) {
            return response(['message' => 'Your vote updated'], Response::HTTP_ACCEPTED);
        }

        return response(['message' => 'You already voted'], Response::HTTP_FORBIDDEN);
    }
}
