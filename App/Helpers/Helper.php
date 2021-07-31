<?php

function response_json(int $status,string $message){
    return response()->json([
        'status'=>$status,
        'message'=>$message
    ]);
}