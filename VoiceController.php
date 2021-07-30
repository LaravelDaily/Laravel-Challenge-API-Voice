<?php

use Illuminate\Foundation\Http\FormRequest;


// You should make sure that the request contains the Accept: application/json header
class VoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_id' => 'required|int|exists:questions,id',
            'value'       => 'required|boolean',
        ];
    }
}

public function voice(VoiceRequest $request)
{

    $question = Question::find($request->post('question_id'));

    if (!$question)
        return response()->json([
            'status'  => 404,
            'message' => 'not found question ..'
        ]);

    if ($question->user_id === auth()->id())
        return response()->json([
            'status'  => 500,
            'message' => 'The user is not allowed to vote to your question'
        ]);

    //check if user voted
    // If we don't use lock the user The user will be able to send a "double" request, for example, by pressing the button 2 times, or by programmatically sending 2 requests at the same time. And both requests in this case will be validated, and it turns out that the data will change 2 times. It is especially dangerous when we subtract or add balance.
    // in this case, lockForUpdate is optional, since voice will be updated 2 times with the same number.
    $voice = Voice::where([
        ['user_id', '=', auth()->id()],
        ['question_id', '=', $request->post('question_id')]
    ])->lockForUpdate()->first();

    if (!is_null($voice) && $voice->value === $request->post('value')) {
        return response()->json([
            'status'  => 500,
            'message' => 'The user is not allowed to vote more than once'
        ]);
    } else if (!is_null($voice) && $voice->value !== $request->post('value')) {
        $voice->update([
            'value' => $request->post('value')
        ]);
        return response()->json([
            'status'  => 201,
            'message' => 'update your voice'
        ]);
    }

    $question->voice()->create([
        'user_id' => auth()->id(),
        'value'   => $request->post('value')
    ]);

    return response()->json([
        'status'  => 200,
        'message' => 'Voting completed successfully'
    ]);
}