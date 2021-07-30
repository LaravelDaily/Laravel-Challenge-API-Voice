<?php

public function checkVoice($request,$question){

      //get voice by question relationship
	  $voice=$question->voice()->where('user_id','=',auth()->id())->first(); 

    if (!is_null($voice)&&$voice->value===$request->post('value')) {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote more than once'
        ]);

    }
    else if (!is_null($voice)&&$voice->value!==$request->post('value')){
        $voice->update([
            'value'=>$request->post('value')
        ]);
        
        return response()->json([
            'status'=>201,
            'message'=>'update your voice'
        ]);
    }

}



?>