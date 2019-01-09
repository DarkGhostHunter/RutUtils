<?php

namespace DarkGhostHunter\RutUtils;

use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;

class RutHelper
{

    /**
     * Where to draw the line between person and company RUTs
     *
     * @var int
     */
    public const COMPANY_RUT_BASE = 50000000;

    /**
     * Cleans a RUT string
     *
     * @param string $rut
     * @param bool $forceUppercase
     * @return string
     */
    public static function cleanRut(string $rut, bool $forceUppercase = true)
    {
        // Filter the RUT string and return only numbers and verification digit.
        $filtered = preg_filter('/(?!\d|k)./i', '', $rut) ?? $rut;

        // If the filtered RUT is not empty and over the 6 characters, we're good.
        if (!empty($filtered))
            return $forceUppercase ? strtoupper($filtered) : strtolower($filtered);
    }

    /**
     * Separate a RUT string into an array
     *
     * @param string $string
     * @return array
     */
    public static function explodeByLastChar(string $string)
    {
        return str_split($string, strlen($string) - 1);
    }

    /**
     * Cleans a RUT from invalid characters and separates it
     *
     * @param string $rut
     * @param bool $uppercase
     * @return array
     * @throws InvalidRutException
     */
    public static function separateRut(string $rut, bool $uppercase = true)
    {
        // Throw an exception if after cleaning the RUT we receive null, since
        // we cannot separate an empty string, thus making the resulting
        // array impossible to return. Also, it makes it catchable.
        if (empty($cleaned = self::cleanRut($rut, $uppercase))) {
            throw new InvalidRutException($rut);
        }

        $array = self::explodeByLastChar($cleaned);

        $array[0] = (int)$array[0];

        return $array;
    }

    /**
     * Returns if a RUT or an array of RUTs are valid
     *
     * @param array $ruts
     * @return bool
     */
    public static function validate(...$ruts)
    {
        if (is_array($ruts[0]) && func_num_args() === 1) {
            $ruts = $ruts[0];
        }

        return self::performValidate($ruts);
    }

    /**
     * Returns if a RUT or an array of RUTs are strictly valid
     *
     * @param mixed ...$ruts
     * @return bool
     */
    public static function validateStrict(...$ruts)
    {
        if (is_array($ruts[0]) && func_num_args() === 1) {
            $ruts = $ruts[0];
        }

        return self::performValidateStrict($ruts);
    }

    /**
     * Performs the lazy validation of the RUTs
     *
     * @param array $ruts
     * @return bool
     */
    protected static function performValidate(array $ruts)
    {
        foreach ($ruts as $rut) {
            if (!self::validateRut($rut)) {
                return false;
            };
        }
        return true;
    }

    /**
     * Performs the strict validation of the RUTs
     *
     * @param array $ruts
     * @return bool
     */
    protected static function performValidateStrict(array $ruts)
    {
        foreach ($ruts as $rut) {

            if (!preg_match('/(\d){1,2}.\d{3}.\d{3}\-[\dkK]/', $rut)) {
                return false;
            }

            if (!self::validateRut($rut)) {
                return false;
            };
        }
        return true;
    }

    /**
     * Validates a RUT
     *
     * @param string $rut
     * @return bool
     */
    protected static function validateRut(string $rut)
    {
        try {
            [$num, $vd] = self::separateRut($rut);
        } catch (InvalidRutException $exception) {
            return false;
        }

        if ($vd != self::getVd($num)) {
            return false;
        }

        return true;
    }

    /**
     * Return if two or more RUTs are equal
     *
     * @param array $ruts
     * @return bool
     */
    public static function isEqual(...$ruts)
    {
        // If the user passed down one single argument as an array, we will use
        // that and unwrap it.
        if (is_array($ruts[0]) && func_num_args() === 1) {
            $ruts = $ruts[0];
        }

        // First, restore the keys of the array.
        $ruts = array_values($ruts);

        // Clean every value
        foreach ($ruts as &$rut) {
            $rut = self::cleanRut($rut);
        }

        // To see if all the ruts are equal we will remove the duplicates values.
        // Doing this should reduce the array to only 1 non-empty item, which
        // means that all the ruts are equal. Otherwise, they're not equal.
        if (count($ruts = array_unique($ruts)) === 1 && !empty($ruts[0])) {
            return true;
        }

        return false;
    }

    /**
     * Filter a given array of RUTs for the correct ones.
     *
     * @param mixed ...$ruts
     * @return array
     */
    public static function filter(...$ruts)
    {
        if (is_array($ruts[0]) && func_num_args() === 1) {
            $ruts = $ruts[0];
        }

        return array_filter($ruts, function ($rut) {
            return self::validate($rut);
        });
    }

    /**
     * Return a complete valid RUT from a number
     *
     * @param int $num
     * @return Rut
     * @throws InvalidRutException
     */
    public static function rectify(int $num)
    {
        return new Rut($num . self::getVd($num));
    }

    /**
     * Return if the given RUT is for a person
     *
     * @param string $rut
     * @return bool
     * @throws InvalidRutException
     */
    public static function isPerson(string $rut)
    {
        list($num) = self::separateRut($rut);

        return $num < self::COMPANY_RUT_BASE && $num > 1000000;
    }

    /**
     * Return if the given RUT is for a company
     *
     * @param string $rut
     * @return bool
     * @throws InvalidRutException
     */
    public static function isCompany(string $rut)
    {
        return !self::isPerson($rut);
    }

    /**
     * Get the Verification Digit from a given number
     *
     * @internal
     * @param int $num
     * @return int|string
     */
    public static function getVd(int $num)
    {
        $i = 2;
        $sum = 0;

        foreach (array_reverse(str_split($num)) as $v) {
            if ($i === 8) $i = 2;
            $sum += $v * $i;
            ++$i;
        }

        $dig = 11 - ($sum % 11);

        if ($dig === 11) $dig = 0;
        if ($dig === 10) $dig = 'K';

        return $dig;
    }
}