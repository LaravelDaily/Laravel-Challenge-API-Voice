<?php

namespace App\Exceptions\Domain;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class VoteOnlyOnce extends DomainException implements HttpExceptionInterface
{
    protected $message = 'The user is not allowed to vote more than once';

    protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
}
