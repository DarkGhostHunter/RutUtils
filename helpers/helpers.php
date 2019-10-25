<?php

use DarkGhostHunter\RutUtils\Rut;
use DarkGhostHunter\RutUtils\RutGenerator;

if (!function_exists('rut')) {

    /**
     * Returns a Rut instance or null, or the Rut Generator if there are no parameters
     *
     * @param  mixed ...$rut
     * @param  null $default
     * @return \DarkGhostHunter\RutUtils\Rut|\DarkGhostHunter\RutUtils\RutGenerator
     */
    function rut($rut = null, $default = null)
    {
        if ($rut) {
            return Rut::makeOr($rut, null, $default);
        }

        return new RutGenerator();
    }
}