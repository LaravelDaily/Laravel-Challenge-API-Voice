<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 404,
            'message'=>'not found question ..'
        ], 200);
    }
}
