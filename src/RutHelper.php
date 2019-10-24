<?php

namespace DarkGhostHunter\RutUtils;

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
     * @param bool $uppercase
     * @return string
     */
    public static function cleanRut(string $rut, bool $uppercase = true)
    {
        // Filter the RUT string and return only numbers and verification digit.
        $filtered = preg_filter('/(?!\d|k)./i', '', $rut) ?? $rut;

        // If the filtered RUT is not empty and over the 6 characters, we're good.
        if (empty($filtered)) {
            return null;
        }

        return $uppercase ? strtoupper($filtered) : strtolower($filtered);
    }

    /**
     * Cleans a RUT from invalid characters and separates it
     *
     * @param string $rut
     * @param bool $uppercase
     * @return array
     */
    public static function separateRut(string $rut, bool $uppercase = true)
    {
        if (empty($cleaned = static::cleanRut($rut, $uppercase))) {
            return [null, null];
        }

        $array = static::explodeByLastChar($cleaned);

        $array[0] = (int)$array[0];

        return $array;
    }

    /**
     * Separate a RUT string into an array
     *
     * @param string $string
     * @return array
     */
    protected static function explodeByLastChar(string $string)
    {
        return str_split($string, strlen($string) - 1);
    }

    /**
     * Returns if all the RUTs in an array are valid
     *
     * @param array $ruts
     * @return bool
     */
    public static function validate(...$ruts)
    {
        return static::performValidateLazy(static::unpack($ruts));
    }

    /**
     * Performs the lazy validation of the RUT strings
     *
     * @param array $ruts
     * @return bool
     */
    protected static function performValidateLazy(array $ruts)
    {
        foreach ($ruts as $rut) {
            if (!static::validateRut($rut)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns if all the RUTs in an array are strictly formatted and valid
     *
     * @param mixed ...$ruts
     * @return bool
     */
    public static function validateStrict(...$ruts)
    {
        return static::performValidateStrict(static::unpack($ruts));
    }

    /**
     * Performs the strict validation of the RUT strings
     *
     * @param array $ruts
     * @return bool
     */
    protected static function performValidateStrict(array $ruts)
    {
        foreach ($ruts as $rut) {
            if (!preg_match('/(\d){1,2}.\d{3}.\d{3}-[\dkK]/', $rut) || !static::validateRut($rut)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates a RUT string
     *
     * @param  int|string $rut
     * @param  string|null $vd
     * @return bool
     */
    protected static function validateRut($rut, $vd = null)
    {
        [$num, $vd] = $vd ? [$rut, $vd] : static::separateRut($rut);

        return $num && $vd && strtoupper($vd) === (string)static::getVd($num);
    }

    /**
     * Filter a given array of RUTs for the correct ones.
     *
     * @param mixed ...$ruts
     * @return array
     */
    public static function filter(...$ruts)
    {
        return array_filter(static::unpack($ruts), function ($rut) {
            [$num, $vd] = is_array($rut) ? $rut : [$rut, null];
            return static::validateRut($num, $vd);
        });
    }

    /**
     * Return a complete RUT instance from a single number
     *
     * @param int $num
     * @return Rut
     */
    public static function rectify(int $num)
    {
        return new Rut($num, static::getVd($num));
    }

    /**
     * Return if the given RUT is for a person (below 50.000.000-7)
     *
     * @param  string $rut
     * @param  null $vd
     * @return bool
     */
    public static function isPerson(string $rut, $vd = null)
    {
        [$num] = $vd ? [$rut, $vd] : static::separateRut($rut);

        return $num && $num < static::COMPANY_RUT_BASE && $num > 1000000;
    }

    /**
     * Return if the given RUT is for a company (equal or above 50.000.000-7)
     *
     * @param  string $rut
     * @param  null $vd
     * @return bool
     */
    public static function isCompany(string $rut, $vd = null)
    {
        return !static::isPerson($rut, $vd);
    }

    /**
     * Return if two or more RUTs are equal
     *
     * @param array $ruts
     * @return bool
     */
    public static function isEqual(...$ruts)
    {
        $ruts = static::unpack($ruts);

        // Clean every value
        foreach ($ruts as $key => $value) {
            $ruts[$key] = static::cleanRut($value);
        }

        // To see if all the ruts are equal we will remove the duplicates values.
        // Doing this should reduce the array to only 1 non-empty item, which
        // means that all the ruts are equal. Otherwise, they're not equal.
        $ruts = array_unique($ruts);

        return count($ruts) === 1 && !empty($ruts[0]);
    }

    /**
     * Unpacks an array of Ruts
     *
     * @param $ruts
     * @return array|mixed
     */
    public static function unpack(array $ruts)
    {
        if (is_array($ruts[0]) && count($ruts) === 1) {
            $ruts = $ruts[0];
        }

        return $ruts;
    }

    /**
     * Get the Verification Digit from a given number
     *
     * @internal This is the main logic to create a valid rut from a number.
     * @param int $num
     * @return int|string
     */
    public static function getVd(int $num)
    {
        $i = 2;
        $sum = 0;

        foreach (array_reverse(str_split($num)) as $v) {
            if ($i === 8) {
                $i = 2;
            }
            $sum += $v * $i;
            ++$i;
        }

        $dig = 11 - ($sum % 11);

        switch ($dig) {
            case 11:
                return 0;
            case 10:
                return 'K';
            default:
                return $dig;
        }
    }
}