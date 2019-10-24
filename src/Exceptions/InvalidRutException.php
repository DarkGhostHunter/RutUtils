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
    public function __construct($expected = 'all', $actual = 'not all', array $ruts)
    {
        $message = $expected === 1
            ? "The RUT [$ruts[0]] is invalid."
            : "Processed $expected RUTs but $actual are valid.";

        parent::__construct($message);
    }
}