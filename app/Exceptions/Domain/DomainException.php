<?php


namespace App\Exceptions\Domain;

use Exception;

abstract class DomainException extends Exception
{
    public function getStatusCode()
    {
        return $this->statusCode ?? 500;
    }

    public function getHeaders()
    {
        return $this->headers ?? [];
    }

    /**
     * Render the exception into an HTTP JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'status' => $this->getStatusCode(),
            'message' => $this->getMessage()
        ]);
    }
}
