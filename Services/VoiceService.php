<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\VoiceRequest;
use App\Repository\QuestionRepository;
use App\Repository\VoteRepository;
use App\Models\Voice;
use App\Models\User;
use Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VoiceService
{
    public function __construct(private VoiceRequest $request, private QuestionRepository $questionRepository, private VoteRepository $voteRepository)
    {
    }

    public function createVoice(User $user): Voice
    {
        $question = $this->questionRepository->getById($this->request->post('question_id'));
        
        //@todo move to a middleware
        if ($question->user_id == $user->id) {
            throw new AccessDeniedHttpException('The user is not allowed to vote to your question');
        }
        
        $userVote = $this->voteRepository->getUserVote($user->id, $question->id);

        //@todo move to a middleware
        if (null !== $userVote && $userVote->value === $this->request->post('value')) {
            throw new AccessDeniedHttpException('The user is not allowed to vote more than once');
        }

        if (null !== $userVote && $userVote->value !== $this->request->post('value')) {
            $userVote->update([
                'value' => $this->request->post('value')
            ]);
            return $userVote;
        }

        return $question->voice()->updateOrCreate(
            ['id' => $userVote ? $userVote->id : -1],
            [
                'user_id' => $user->id,
                'value' => $this->request->post('value')
            ]
        );
    }
}
