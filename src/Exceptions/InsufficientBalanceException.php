<?php

namespace Enrow\Exceptions;

class InsufficientBalanceException extends EnrowException
{
    public function __construct(string $message = 'Your credit balance is insufficient.')
    {
        parent::__construct($message, 402, 'InsufficientBalance');
    }
}
