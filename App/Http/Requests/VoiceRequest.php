$question = Question::find(request['question_id']);
request('auth_id') = auth()->user()->id;
request('question_user_id') = $question->user_id;

return [
    'question_id'=>'required|int|exists:questions,id',
    'value'=>'required|boolean',
    'auth_id'=>'different:question_user_id'
];



public function messages(){
    return [
        'auth_id.different' => response_json(500,'The user is not allowed to vote to your question')
    ];
}