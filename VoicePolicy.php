<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class VoicePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update(Question $question)
    {
        return $question->user_id !== auth()->id();
    }
}
