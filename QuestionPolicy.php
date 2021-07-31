<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function vote(User $user, Question $question)
    {
        if ($user->id != $question->user_id) {
            return true;
        }

        return $this->deny('You cannot vote for your own question.');
    }
}
