use Services\QuestionService;
use Services\VoiceService;

<?php
public function voice(Request $request){
  
  //perform question related operations into seprate quetion service class
  QuestionService::question($request);

   //find question by id
   $question=QuestionService::findQuestion($request);
    
    //perform voice related operations into seprate voice service class
   VoiceService::checkVoice($request,$question);

    $question->voice()->create([
        'user_id'=>auth()->id(),
        'value'=>$request->post('value')
    ]);

    return response()->json([
        'status'=>200,
        'message'=>'Voting completed successfully'
    ]);
}
?>