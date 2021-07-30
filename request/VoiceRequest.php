<?php

public function $rules()
{
    return [
        'question_id'=>'required|int|exists:questions,id',
        'value'=>'required|boolean'
    ];
}