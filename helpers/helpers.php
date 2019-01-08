<?php

if (!function_exists('is_rut')) {

    /**
     * Returns if a RUT or an array of RUTs are valid
     *
     * @param mixed ...$rut
     * @return bool
     */
    function is_rut(...$rut)
    {
        return \DarkGhostHunter\RutUtils\RutHelper::validate(...$rut);
    }
}

if (!function_exists('is_rut_strict')) {

    /**
     * Returns if a RUT or an array of RUTs are strictly valid
     *
     * @param mixed ...$rut
     * @return bool
     */
    function is_rut_strict(...$rut)
    {
        return \DarkGhostHunter\RutUtils\RutHelper::validateStrict(...$rut);
    }
}

if (!function_exists('is_rut_equal')) {

    /**
     * Return if two or more RUTs are equal
     *
     * @param array $ruts
     * @return bool
     */
    function is_rut_equal(...$ruts)
    {
        return \DarkGhostHunter\RutUtils\RutHelper::isEqual(...$ruts);
    }
}

if (!function_exists('rut_filter')) {

    /**
     * Filter a given array of RUTs for the correct ones
     *
     * @param mixed ...$ruts
     * @return array
     */
    function rut_filter(...$ruts) {
        return \DarkGhostHunter\RutUtils\RutHelper::filter(...$ruts);
    }
}

if (!function_exists('rut_rectify')) {

    /**
     * Return a complete valid RUT from a number
     *
     * @param int $num
     * @return \DarkGhostHunter\RutUtils\Rut
     * @throws \DarkGhostHunter\RutUtils\Exceptions\InvalidRutException
     */
    function rut_rectify(int $num) {
        return \DarkGhostHunter\RutUtils\RutHelper::rectify($num);
    }
}

if (!function_exists('is_rut_person')) {

    /**
     * Return if the given RUT is for a person
     *
     * @param string $rut
     * @return bool
     * @throws \DarkGhostHunter\RutUtils\Exceptions\InvalidRutException
     */
    function is_rut_person(string $rut) {
        return \DarkGhostHunter\RutUtils\RutHelper::isPerson($rut);
    }
}

if (!function_exists('is_rut_company')) {

    /**
     * Return if the given RUT is for a company
     *
     * @param string $rut
     * @return bool
     * @throws \DarkGhostHunter\RutUtils\Exceptions\InvalidRutException
     */
    function is_rut_company(string $rut) {
        return \DarkGhostHunter\RutUtils\RutHelper::isCompany($rut);
    }
}

if (!function_exists('rut_clean')) {

    /**
     * Cleans a RUT string
     *
     * @param string $rut
     * @return string
     */
    function rut_clean(string $rut) {
        return \DarkGhostHunter\RutUtils\RutHelper::cleanRut($rut);
    }
}

