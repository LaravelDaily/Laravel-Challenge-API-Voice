<?php


namespace App\Services;


class VoteServive
{
    // check if user voted returns error or if allowed to vote updates the vote or creates one
    public function validateOrUpdateOrCreate($request,$question)
    {
        $validation = $this->validate($request);

        if ( $validation['needs update'] ) {
            return $this->update($validation['needs update'],$request['value']);
        }elseif ($validation['error']) {
            return $validation['error'];
        }

        return $this->create($question,$request['value']);
    }

    public function update($voice,string $value)
    {
        $voice->update([
            'value'=>$value
        ]);

        return response_json(201,'update your voice');
    }

    public function create($question,$value)
    {
        $question->voice()->create([
            'value'=>$value
        ]);

        return response_json(200,'Voting completed successfully');
    }

    public function validate($request)
    {
        $voice=Voice::findQuestion($request['question_id'])->first();

        if ( !is_null($voice)&&$voice->value!==$request['value'] ) {
            return ['needs update'=>$voice];

        }elseif( is_null($voice)&&$voice->value==$request['value'] ){
            return [
                'error'=>response_json(500,'The user is not allowed to vote more than once')
            ];
        }
    }
}