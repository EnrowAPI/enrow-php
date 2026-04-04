<?php

namespace Enrow\Exceptions;

class AuthenticationException extends EnrowException
{
    public function __construct(string $message = 'Invalid or missing API key')
    {
        parent::__construct($message, 401, 'Unauthorized');
    }
}
