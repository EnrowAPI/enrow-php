<?php

namespace Enrow\Exceptions;

class RateLimitException extends EnrowException
{
    public function __construct(string $message = 'Rate limit exceeded')
    {
        parent::__construct($message, 429, 'RateLimitExceeded');
    }
}
