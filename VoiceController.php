<?php

public function submitVote_NeedsKeepJsonResponse(Request $request) // If, for some reason, the json response needs to keep as it is (needs to work with a old version app, for example). Why "voice"? Isn't this a quizz like thing? Vote typo?
  {
    $request->validate([
      'question_id' => 'required|int|exists:questions,id',
      'value' => 'required|boolean',
    ]);

    $question = Question::find($request->post('question_id'));
    if (!$question)
      return response()->json([
        'status' => 404,
        'message' => 'Question was not found!'
      ]);
    if ($question->user_id == auth()->id()) // I hope this is inside a Middleware that ensures the user is logged in.
      return response()->json([
        'status' => 500,
        'message' => "The user is not allowed to vote on it's own question"
      ]);


    $voice = Voice::firstOrCreate([ // Maybe change model's name? Unless it's actually voice for some reason?
      'user_id' => auth()->id(),
      'question_id' => $request->post('question_id')
    ]);

    if ($voice->wasRecentlyCreated) { // If it was created by CREATE parte of firstOrCREATE
      return response()->json([
        'status' => 201,
        'message' => 'You have voted successfully!'
      ]);
    }

    if ($voice->value === $request->post('value')) {
      return response()->json([
        'status' => 500,
        'message' => 'There is nothing to change here!'
      ]);
    } else {
      $voice->update([
        'value' => $request->post('value')
      ]);
      return response()->json([
        'status' => 201,
        'message' => 'You have changed your vote!'
      ]);
    }
  }

  public function submitVote_CanChangeResponse(Request $request) // If the user is allowed to change the response. Allows readable "one line" if/returns. Now... Why "voice"? Isn't this a quizz like thing? Vote typo?
  {
    $request->validate([
      'question_id' => 'required|int|exists:questions,id',
      'value' => 'required|boolean',
    ]);

    $question = Question::find($request->post('question_id')); // FindOrFail could be used with try catch... But longer controller
    if (!$question) return response()->json(['message' => 'Question was not found'], 404);

    if ($question->user_id == auth()->id()) return response()->json(['message' => "The user is not allowed vote on it's own question", 406]); // No need "status" code with 200 beeing the actual request code? But if you need, just add to the array.I changed the error code to 406. "406 Not Acceptable". It was 500, which can be thrown by a web server like nginx/apache for other reasons.

    $vote = Voice::firstOrCreate([ // Maybe change model's name? Unless it's actually voice for some reason?
      'user_id' => auth()->id(),
      'question_id' => $request->post('question_id')
    ],[
      'value' => $request->post('value') // Forgot this. This tells that, if it needs to create, it will create with a value on this field 
    ]);

    if ($vote->wasRecentlyCreated) return response()->json(['You have voted successfully!'], 201); //// If it was created by CREATE parte of firstOrCREATE

    if ($vote->value === $request->post('value')) return response()->json(['message' => 'There is nothing to change here!', 200]);

    $vote->update([
      'value' => $request->post('value')
    ]);

    return response()->json(['message' => 'You have changed your vote!'], 215); // User can send a custom status code 😁, but probably 202 is advised here?
  } // This is how I would have done it.
