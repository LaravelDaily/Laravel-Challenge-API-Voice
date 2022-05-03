<?php

namespace App\Exceptions;

use Exception;

class NotAllowedException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 500,
            'message' => 'The user is not allowed to vote to your question'
        ], 200);

    }
}
