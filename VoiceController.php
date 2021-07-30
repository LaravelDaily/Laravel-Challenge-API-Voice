<?php
class TestController extends Controller
{
    public function voice(Request $request)
    {
        $request->validate([
            'question_id' => 'required|int|exists:questions,id',
            'value' => 'required|boolean'
        ]);

        $question = Question::findOrFail($request->question_id);

        abort_if(
            $question->user_id == auth()->id(),
            403,
            'The user is not allowed to vote to your question'
        );

        Voice::updateOrCreate([
            'user_id' => auth()->id(),
            'question_id' => $request->question_id,
        ], [$request->value]);

        return response()->json([
            'status' => 201,
        ]);
    }
}
