<?php

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutGenerator;

if (!function_exists('rut')) {

    /**
     * Returns a Rut instance or null, or the Rut Generator if there are no parameters
     *
     * @param  null|string|array| $rut
     * @param  null|mixed $default
     * @return null|\DarkGhostHunter\RutUtils\Rut|\DarkGhostHunter\RutUtils\RutGenerator
     */
    function rut($rut = null, $default = null)
    {
        return $rut
            ? Rut::make($rut, null, $default)
            : new RutGenerator();
    }
}