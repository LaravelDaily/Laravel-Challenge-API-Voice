# Laravel Challenge: API Controller - Voices

Only minimal files have been added.

## Relevant Files

 - `app/Http/Controllers/QuestionController.php`: Simple Controller with 1 method.
 - `app/Http/Requests/VotingRequest.php`: Form Request with the minimal requirements.
 - `app/Models/User.php`: Add a relationship to Vote model.
 - `app/Models/Voice.php`: Make sure relevant fields are fillable.
 - `app/Providers/AuthServiceProvider.php`: Define a simple Gate to check if an user is authorized or not to vote on a question.
 - `routes/web.php`: Add a route with Route Model Binding.

## The code itself

After offloading the validation to a form request, the authorization to a gate and consolidating the logic with an upsert operation, the end result only 3 statements were needed.

```php
    public function voice(VotingRequest $request, Question $question)
    {
        $this->authorize('vote', $question);

        auth()->user()->voices()->updateOrCreate(
            ['question_id' => $question->id],
            ['value' => $request->value]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Voting completed successfully.'
        ]);
    }
```