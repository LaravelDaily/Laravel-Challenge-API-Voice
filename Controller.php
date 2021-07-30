<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class Controller
{
    /**
     * Send JSON Response
     *
     * @param array $data
     * @param integer $code
     * @return Response
     */
    protected function sendJsonResponse(array $data, int $code): Response
    {
        return response()->json([
            "code" => $code,
            "data" => $data
        ], $code);
    }

    /**
     * Send JSON Error Response
     *
     * @param string $message
     * @param integer $code
     * @return Response
     */
    protected function sendErrorResponse(string $message, int $code): Response
    {
        return response()->json([
            "message" => $message,
            "code" => $code
        ], $code);
    }
}
