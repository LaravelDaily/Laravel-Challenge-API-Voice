<?php


namespace App\Usecases;

use App\Exceptions\Domain\QuestionNotFound;
use App\Exceptions\Domain\VoteNotAllowed;
use App\Exceptions\Domain\VoteOnlyOnce;
use App\Http\Requests\VoiceRequest;
use App\Models\Question;
use App\Models\Voice;

class NewVoiceFromUser
{
    protected $request;
    protected $question;
    protected $voice;

    public function __construct(VoiceRequest $request)
    {
        $this->question = Question::findOrFail($request->question_id);

        if ($this->question->user_id === auth()->id()) {
            throw new VoteNotAllowed();
        }

        $this->voice = Voice::where([
            ['user_id', '=', auth()->id()],
            ['question_id', '=', $this->question->id]
        ])->first();

        if ($this->voice && $this->voice->value === $request->value) {
            throw new VoteOnlyOnce();
        }

        $this->request = $request;
    }

    public function updateOrCreateVote()
    {
        if ($this->voice && $this->voice->value !== $this->request->value) {
            $this->voice->update([
                'value' => $this->request->value
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'update your voice'
            ]);
        }

        Voice::create([
            'question_id' => $this->request->question_id,
            'user_id' => auth()->id(),
            'value' => $this->request->value
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Voting completed successfully'
        ], 201);

    }

}
