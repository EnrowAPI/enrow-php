<?php

namespace Enrow\Exceptions;

class EnrowException extends \Exception
{
    public int $status;
    public string $error;

    public function __construct(string $message, int $status = 400, string $error = 'EnrowError')
    {
        parent::__construct($message, $status);
        $this->status = $status;
        $this->error = $error;
    }
}
