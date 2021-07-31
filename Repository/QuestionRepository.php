<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Question;

class QuestionRepository
{
    public function getById(?int $id): Question
    {
        return Question::findOrFail($id);
    }
}
