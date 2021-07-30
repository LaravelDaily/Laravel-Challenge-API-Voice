<?php

class User extends Model
{
    public function voices()
    {
        return $this->hasMany(Voice::class);
    }

    public function votedFor($question_id)
    {
        return $this->voices->whereQuestoinId($question_id)->first();
    }
}

class Question extends Model
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isMine()
    {
        return $this->creator->id === Auth::id();
    }

    public function voices()
    {
        return $this->hasMany(Voice::class);
    }

    public function addVote($user_id, $value)
    {
        $this->voices->create(compact('user_id', 'value'));
    }
}

class VoiceController extends controller
{

    public function voice(Request $request, Question $question)
    {
        $request->validate([
            'value' => 'required|boolean'
        ]);

        if ($question->isMine()) {
            return response()->json('you can\'t vote for yourself', 401);
        }

        if ($myVote = $request->user()->votedFor($question->id)) {
            if ($myVote->value === $request->value) {
                return response()->json('The user is not allowed to vote more than once', 401);
            } else {
                $myVote->update([
                    'value' => $request->value
                ]);
                return response()->json('update your voice', 201);
            }
        }

        return $question->addVote($request->user(), $request->value);
    }
}
