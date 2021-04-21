<?php

namespace DarkGhostHunter\RutUtils\Exceptions;

use Exception;

class InvalidRutException extends Exception
{
    /**
     * Create a new InvalidRutException instance.
     *
     * @param  string|null $rut
     */
    public function __construct($rut = null)
    {
        parent::__construct(is_string($rut) ? "The given RUT [$rut] is invalid" : 'The given RUT is invalid');
    }
}