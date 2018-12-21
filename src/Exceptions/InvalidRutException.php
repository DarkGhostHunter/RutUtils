<?php

namespace DarkGhostHunter\RutUtils\Exceptions;

use Exception;
use Throwable;

class InvalidRutException extends Exception
{

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = "Cannot make an invalid RUT: $message";

        parent::__construct($message, $code, $previous);
    }

}