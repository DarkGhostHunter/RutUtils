<?php

if (!function_exists('is_rut')) {
    function is_rut(...$rut)
    {
        return \DarkGhostHunter\RutUtils\RutHelper::validate(...$rut);
    }
}

if (!function_exists('is_rut_equal')) {
    function is_rut_equal(string $rutA, string $rutB)
    {
        return \DarkGhostHunter\RutUtils\RutHelper::isEqual($rutA, $rutB);
    }
}

if (!function_exists('rut_filter')) {
    function rut_filter(...$ruts) {
        return \DarkGhostHunter\RutUtils\RutHelper::filter(...$ruts);
    }
}

if (!function_exists('rut_rectify')) {
    function rut_rectify(int $num) {
        return \DarkGhostHunter\RutUtils\RutHelper::rectify($num);
    }
}

if (!function_exists('is_rut_person')) {
    function is_rut_person(string $rut) {
        return \DarkGhostHunter\RutUtils\RutHelper::isPerson($rut);
    }
}

if (!function_exists('is_rut_company')) {
    function is_rut_company(string $rut) {
        return \DarkGhostHunter\RutUtils\RutHelper::isCompany($rut);
    }
}