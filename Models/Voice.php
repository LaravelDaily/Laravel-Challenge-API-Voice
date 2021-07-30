<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function scopeOfUser($query, User|int $user)
    {
        $user_id = $user instanceof User ? $user->id : $user;
        return $query->where('user_id', $user_id);
    }

    public function scopeOfQuestion($query, Question|int $question)
    {
        $question_id = $question instanceof Question ? $question->id : $question;
        return $query->where('question_id', $question_id);
    }

    public function createForUser(User|int $user, $value)
    {
        $user_id = $user instanceof User ? $user->id : $user;
        return $this->create([
            'user_id' => $user_id,
            'value' => $value,
        ]);
    }

    public function hasValueOf($value)
    {
        return $this->value === $value;
    }

    public function updateValue($value)
    {
        return $this->update([
            'value'=>$value
        ]);
    }
}