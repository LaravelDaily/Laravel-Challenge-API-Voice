<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Voice;

class VoteRepository
{
    public function getUserVote(int $userId, int $questionId): ?Voice
    {
        return Voice::where([
            ['user_id', '=', $userId],
            ['question_id', '=', $questionId]
        ])
        ->first();
    }
}
