<?php

namespace App\Policies;

class VoicePolicy
{
    use HandlesAuthorization;

    public function vote(User $user, Question $question)
    {
        return $question->user_id != $user->id;
    }
}
