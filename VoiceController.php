<?php
public function voice(Request $request){

    $request->validate([
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean',
    ]);

    try {
        $question = Question::findOrFail($request->post('question_id'));

        $vote = $question->vote($request->post('value'));
        $responseData = $vote->wasRecentlyCreated
            ? ['status' => 200, 'message' => 'Voting completed successfully']
            : ['status' => 201, 'message' => 'update your voice'];
    } catch(ModelNotFoundException $e) {
        // We should be able to remove this catch. Validation should assure we never get to this?
        $responseData = ['status' => 401, 'message' => 'Question not found.'];
    } catch (IsOwnQuestionException $e) {
        $responseData = ['status' => 401, 'message' => 'The user is not allowed to vote to your question'];
    } catch (UserAlreadyHasVotedException $e) {
        $responseData = ['status' => 500, 'message' => 'The user is not allowed to vote more than once'];
    }

    return response()->json($responseData);
}

class User extends Model {

    public function hasAlreadyVotedForQuestion(Question $question, $value)
    {
            return $question
                ->voice()
                ->where('user_id', $this->id)
                ->where('value', $value)
                ->exists();
    }

    public function questionBelongsToUser(Question $question)
    {
        return $this->id === $question->user_id;
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

class Question extends Model {

    public function vote($value)
    {
        throw_if(auth()->questionBelongsToUser($this), IsOwnQuestionException::class);

        throw_if(auth()->hasAlreadyVotedForQuestion($this, $value), UserAlreadyHasVotedException::class);

        return Voice::updateOrCreate(
            ['user_id' => auth()->id],
            ['value' => $value]
        );
    }
}