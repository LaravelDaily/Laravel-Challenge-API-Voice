//move validates to QuestionRequest
public function voice(QuestionRequest $request): JsonResponse
{
    $request = $request->validated();

    $question = Question::whereHas('voice', function ($query) use ($request) {
        $query->where('user_id', auth()->id());
    })
            ->where('user_id', '!=', auth()->id())
            ->where('id', $request['question_id'])
            ->first();

    if ($question) {
        $question->voice()->update([
                'value' => $request('value')
        ]);

        return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'update your voice'
        ]);
    } else {
        $question->voice->create([
                'user_id' => auth()->id(),
                'value' => $request['value']
        ]);

        return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Voting completed successfully'
        ]);
    }
}
