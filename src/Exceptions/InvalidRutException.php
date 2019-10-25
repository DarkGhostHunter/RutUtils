<?php

namespace DarkGhostHunter\RutUtils\Exceptions;

use Exception;

class InvalidRutException extends Exception
{
    /**
     * Create a new InvalidRutException instance.
     *
     * @param $expected
     * @param $actual
     * @param  array $ruts
     */
    public function __construct(array $ruts, $expected = 'all', $actual = 'not all')
    {
        $message = $expected === 1
            ? 'The RUT is invalid.'
            : "Processed $expected RUTs but $actual are valid.";

        parent::__construct($message);
    }
}